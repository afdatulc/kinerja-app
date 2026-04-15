<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Indikator;
use App\Models\OutputMaster;
use App\Models\OutputRealisasi;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
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
            'triwulan' => 'required|integer|between:1,4',
            'tahun' => 'required|integer|min:2025',
            'nama_satker' => 'required|string|max:255',
        ]);

        $tw = $request->triwulan;
        $tahun = $request->tahun;
        $satker = $request->nama_satker;

        // 1. Ambil data Indikator & Realisasi
        $indikators = Indikator::where('tahun', $tahun)
            ->with(['target', 'realisasis' => function($q) use ($tw) {
                $q->where('triwulan', $tw);
            }])
            ->get();

        // 2. Hitung Rata-rata Capaian Kinerja (IKU)
        $totalCapaian = 0;
        $countIku = 0;
        
        foreach ($indikators as $ind) {
            $targetTwField = "target_tw" . $tw;
            $targetVal = $ind->target ? $ind->target->$targetTwField : 0;
            $realisasiVal = $ind->realisasis->first() ? $ind->realisasis->first()->realisasi_kumulatif : 0;

            if ($targetVal > 0) {
                $capaian = ($realisasiVal / $targetVal) * 100;
                $totalCapaian += $capaian;
                $countIku++;
            }
        }

        $avgCapaian = $countIku > 0 ? round($totalCapaian / $countIku, 2) : 0;

        // 3. Ambil data Analisis
        $analisisGlobal = Analisis::where('triwulan', $tw)
            ->whereHas('indikator', function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })
            ->get();

        // 4. Ambil data Rincian Output
        $outputs = OutputMaster::whereHas('indikator', function($q) use ($tahun) {
            $q->where('tahun', $tahun);
        })
        ->with(['outputRealisasis' => function($q) use ($tw) {
            $q->where('triwulan', $tw);
        }])
        ->get();

        // 5. Proses Word Template
        $templatePath = storage_path('app/templates/template_notulen.docx');
        
        if (!file_exists($templatePath)) {
            // Jika template belum ada, buat folder dan berikan pesan error atau template kosong
            if (!file_exists(storage_path('app/templates'))) {
                mkdir(storage_path('app/templates'), 0755, true);
            }
            return back()->with('error', 'File template_notulen.docx tidak ditemukan di storage/app/templates/');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Set Variabel Global
        $templateProcessor->setValue('nama_satker', $satker);
        $templateProcessor->setValue('periode_tw', $this->formatTriwulan($tw));
        $templateProcessor->setValue('periode_tahun', $tahun);
        $templateProcessor->setValue('avg_capaian', $avgCapaian . '%');

        // Gabungkan Narasi Analisis & Penjelasan (Ambil dari analisis pertama yang ditemukan atau join)
        $narasiAnalisis = $analisisGlobal->pluck('narasi_analisis')->filter()->join("\n");
        $kendala = $analisisGlobal->pluck('kendala')->filter()->join("\n");
        $solusi = $analisisGlobal->pluck('solusi')->filter()->join("\n");
        $rtl = $analisisGlobal->pluck('rencana_tindak_lanjut')->filter()->join("\n");
        $pic = $analisisGlobal->pluck('pic_tindak_lanjut')->filter()->unique()->join(", ");
        $penjelasanLainnya = $analisisGlobal->pluck('penjelasan_lainnya')->filter()->join("\n");

        $templateProcessor->setValue('narasi_analisis', $narasiAnalisis ?: '-');
        $templateProcessor->setValue('kendala', $kendala ?: '-');
        $templateProcessor->setValue('solusi', $solusi ?: '-');
        $templateProcessor->setValue('rtl', $rtl ?: '-');
        $templateProcessor->setValue('pic', $pic ?: '-');
        $templateProcessor->setValue('penjelasan_lainnya', $penjelasanLainnya ?: '-');

        // Tabel Kinerja
        $dataTabelKinerja = [];
        foreach ($indikators as $index => $ind) {
            $targetTwField = "target_tw" . $tw;
            $targetVal = $ind->target ? $ind->target->$targetTwField : 0;
            $realisasiVal = $ind->realisasis->first() ? $ind->realisasis->first()->realisasi_kumulatif : 0;
            $capaian = $targetVal > 0 ? round(($realisasiVal / $targetVal) * 100, 2) : 0;

            $dataTabelKinerja[] = [
                'no' => $index + 1,
                'sasaran' => $ind->sasaran,
                'indikator' => $ind->indikator_kinerja,
                'target_pk' => $ind->target_tahunan,
                'target_tw' => $targetVal,
                'realisasi_tw' => $realisasiVal,
                'capaian_tw' => $capaian . '%',
            ];
        }
        $templateProcessor->cloneRowAndSetValues('no', $dataTabelKinerja);

        // Tabel RO
        $dataTabelRo = [];
        foreach ($outputs as $index => $out) {
            $realisasi = $out->outputRealisasis->first();
            $dataTabelRo[] = [
                'no_ro' => $index + 1,
                'nama_output' => $out->nama_output,
                'volume' => $realisasi ? $realisasi->volume : 0,
                'progres' => $realisasi ? $realisasi->progres . '%' : '0%',
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
