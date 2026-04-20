<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Indikator;
use App\Models\OutputMaster;
use App\Models\OutputRealisasi;
use App\Services\RichTemplateProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotulenController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');
        $years = range(2025, max(2025, $currentYear));

        return view('notulen.index', [
            'years' => $years
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'triwulan'    => 'required|integer|between:1,4',
            'tahun'       => 'required|integer|min:2025',
            'nama_satker' => 'required|string|max:255',
        ]);

        $tw     = $request->triwulan;
        $tahun  = $request->tahun;
        $satker = $request->nama_satker;

        // 1. Ambil data Indikator & Realisasi
        $indikators = Indikator::where('tahun', $tahun)
            ->with(['target', 'realisasis' => function ($q) use ($tw) {
                $q->where('triwulan', $tw);
            }])
            ->get();

        // 2. Hitung Rata-rata Capaian Kinerja
        $totalCapaian = 0;
        $countIku     = 0;

        foreach ($indikators as $ind) {
            $targetTwField = "target_tw" . $tw;
            $targetVal     = $ind->target ? $ind->target->$targetTwField : 0;
            $realisasiVal  = $ind->realisasis->first() ? $ind->realisasis->first()->realisasi_kumulatif : 0;

            if ($targetVal > 0) {
                $capaian       = ($realisasiVal / $targetVal) * 100;
                $totalCapaian += $capaian;
                $countIku++;
            }
        }

        $avgCapaian = $countIku > 0 ? round($totalCapaian / $countIku, 2) : 0;

        // 3. Ambil data Analisis
        $analisisGlobal = Analisis::where('triwulan', $tw)
            ->whereHas('indikator', function ($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })
            ->get();

        // 4. Ambil data Rincian Output
        $outputs = OutputMaster::whereHas('indikator', function ($q) use ($tahun) {
            $q->where('tahun', $tahun);
        })
            ->with(['outputRealisasis' => function ($q) use ($tw) {
                $q->where('triwulan', $tw);
            }])
            ->get();

        // 5. Proses Word Template
        $templatePath = storage_path('app/templates/template_notulen.docx');

        if (!file_exists($templatePath)) {
            if (!file_exists(storage_path('app/templates'))) {
                mkdir(storage_path('app/templates'), 0755, true);
            }
            return back()->with('error', 'File template_notulen.docx tidak ditemukan di storage/app/templates/');
        }

        $templateProcessor = new RichTemplateProcessor($templatePath);

        // Set Variabel Global
        $templateProcessor->setValue('nama_satker', $satker);
        $templateProcessor->setValue('periode_tw', $this->formatTriwulan($tw));
        $templateProcessor->setValue('periode_tahun', $tahun);
        $templateProcessor->setValue('avg_capaian', $avgCapaian . '%');

        // Narasi Analisis & teks lainnya
        $narasiAnalisis   = $analisisGlobal->pluck('narasi_analisis')->filter()->join("\n");
        $kendala          = $analisisGlobal->pluck('kendala')->filter()->join("\n");
        $solusi           = $analisisGlobal->pluck('solusi')->filter()->join("\n");
        $rtl              = $analisisGlobal->pluck('rencana_tindak_lanjut')->filter()->join("\n");
        $pic              = $analisisGlobal->pluck('pic_tindak_lanjut')->filter()->unique()->join(", ");
        $penjelasanLainnya = $analisisGlobal->pluck('penjelasan_lainnya')->filter()->join("\n");
        $batasWaktu       = $analisisGlobal->pluck('batas_waktu')->filter()->join(", ");

        // Tambahan: Isi placeholder tautan global (sesuai screenshot template user)
        $allLinkKinerja = $indikators->pluck('link_bukti_kinerja')->filter()->unique()->join("\n");
        $allLinkTL      = $indikators->pluck('link_bukti_tindak_lanjut')->filter()->unique()->join("\n");
        $allPenjelasanIndikator = $indikators->pluck('penjelasan_lainnya')->filter()->unique()->join("\n");
        
        // Gabungkan penjelasan global (Analisis) dengan penjelasan per-indikator
        $finalPenjelasan = collect([$penjelasanLainnya, $allPenjelasanIndikator])->filter()->join("\n\n");

        $templateProcessor->setValue('narasi_analisis', $narasiAnalisis ?: '-');
        $templateProcessor->setValue('kendala', $kendala ?: '-');
        $templateProcessor->setValue('solusi', $solusi ?: '-');
        $templateProcessor->setValue('rtl', $rtl ?: '-');
        $templateProcessor->setValue('pic', $pic ?: '-');
        $templateProcessor->setValue('penjelasan_lainnya', $finalPenjelasan ?: '-');
        $templateProcessor->setValue('batas_waktu', $batasWaktu ?: '-');

        // Isi placeholder link (Kinerja & Tindak Lanjut)
        $templateProcessor->setValue('link_bukti_kerja', $allLinkKinerja ?: '-');
        
        // Handle specifically if the template uses {link_bukti_tindak_lanjut} without $
        // We set both to be safe
        $templateProcessor->setValue('link_bukti_tindak_lanjut', $allLinkTL ?: '-');
        $templateProcessor->setValue('{link_bukti_tindak_lanjut}', $allLinkTL ?: '-');

        // === DASAR HITUNG & BASIS DATA REALISASI ===
        // Gabungkan HTML dari semua indikator, inject ke Word dengan formatting utuh
        $dasarHitungHtml  = '';
        $indWithDetails = $indikators->filter(function($ind) {
            return !empty(trim(strip_tags($ind->dasar_hitung ?? ''))) ||
                   !empty(trim($ind->penjelasan_lainnya ?? '')) ||
                   !empty(trim($ind->link_bukti_kinerja ?? '')) ||
                   !empty(trim($ind->link_bukti_tindak_lanjut ?? ''));
        });

        if ($indWithDetails->isNotEmpty()) {
            foreach ($indWithDetails as $ind) {
                // Header Indikator
                $header = htmlspecialchars(
                    trim(($ind->kode ? $ind->kode . ' - ' : '') . $ind->indikator_kinerja)
                );
                
                // Hanya masukkan Basis Data & Dasar Hitung jika ada isinya
                if (!empty(trim(strip_tags($ind->dasar_hitung ?? '')))) {
                    $dasarHitungHtml .= $ind->dasar_hitung;
                    $dasarHitungHtml .= '<p>&nbsp;</p>';
                }
            }
        } else {
            $dasarHitungHtml = '<p>-</p>';
        }

        $templateProcessor->setHtmlValue('dasar_hitung', $dasarHitungHtml);

        // Tabel Kinerja
        $dataTabelKinerja = [];
        foreach ($indikators as $index => $ind) {
            $targetTwField = "target_tw" . $tw;
            $targetVal     = $ind->target ? $ind->target->$targetTwField : 0;
            $realisasiVal  = $ind->realisasis->first() ? $ind->realisasis->first()->realisasi_kumulatif : 0;
            $capaian       = $targetVal > 0 ? round(($realisasiVal / $targetVal) * 100, 2) : 0;
            $capaianPk     = $ind->target_tahunan > 0 ? round(($realisasiVal / $ind->target_tahunan) * 100, 2) : 0;

            $dataTabelKinerja[] = [
                'no'          => $index + 1,
                'sasaran'     => $ind->sasaran,
                'indikator'   => $ind->indikator_kinerja,
                'target_pk'   => $ind->target_tahunan,
                'target_tw'   => $targetVal,
                'realisasi_tw' => $realisasiVal,
                'capaian_tw'  => $capaian . '%',
                'capaian_pk'  => $capaianPk . '%',
            ];
        }
        $templateProcessor->cloneRowAndSetValues('no', $dataTabelKinerja);

        // Tabel RO
        $dataTabelRo = [];
        foreach ($outputs as $index => $out) {
            $realisasi     = $out->outputRealisasis->first();
            $dataTabelRo[] = [
                'no_ro'      => $index + 1,
                'nama_output' => $out->nama_output,
                'volume'     => $realisasi ? $realisasi->volume : 0,
                'progres'    => $realisasi ? $realisasi->progres . '%' : '0%',
            ];
        }
        $templateProcessor->cloneRowAndSetValues('no_ro', $dataTabelRo);

        $fileName = "Notulen_Kinerja_{$satker}_TW_{$tw}_{$tahun}.docx";
        $tempPath = storage_path('app/temp_' . $fileName);
        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    private function formatTriwulan($tw)
    {
        $map = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
        return "Triwulan " . ($map[$tw] ?? $tw);
    }
}
