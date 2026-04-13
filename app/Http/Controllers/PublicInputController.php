<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\Pegawai;
use App\Models\Aktivitas;
use App\Models\Analisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicInputController extends Controller
{
    public function index()
    {
        $tahun = request('tahun', 2026);
        $triwulan = request('triwulan', 1);
        
        $indikators = Indikator::where('tahun', $tahun)->get();
        $pegawais = Pegawai::orderBy('nama')->get();
        
        return view('public.index', compact('indikators', 'tahun', 'triwulan', 'pegawais'));
    }


    public function storeKendala(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string',
            'indikator_id' => 'required|exists:indikators,id',
            'triwulan' => 'required|integer|between:1,4',
            'kendala' => 'required|string',
            'severity' => 'required|in:Low,Medium,High',
            'solusi' => 'nullable|string',
            'rencana_tindak_lanjut' => 'nullable|string',
            'pic_tindak_lanjut' => 'nullable|string',
            'batas_waktu' => 'nullable|date',
        ]);

        $pegawai = Pegawai::where('nip', $validated['nip'])
                        ->orWhere('email_bps', $validated['nip'])
                        ->first();

        if (!$pegawai) {
            return redirect()->back()->withErrors(['nip' => 'NIP atau Email tidak terdaftar dalam database kami.'])->withInput();
        }

        Analisis::create([
            'indikator_id' => $validated['indikator_id'],
            'pegawai_nip' => $pegawai->nip ?? $pegawai->email_bps,
            'triwulan' => $validated['triwulan'],
            'kendala' => $validated['kendala'],
            'severity' => $validated['severity'],
            'solusi' => $validated['solusi'],
            'rencana_tindak_lanjut' => $validated['rencana_tindak_lanjut'],
            'pic_tindak_lanjut' => $validated['pic_tindak_lanjut'],
            'batas_waktu' => $validated['batas_waktu'],
        ]);

        return redirect()->back()->with('success', 'Laporan kendala berhasil dikirim.');
    }

    public function getKegiatan($indikator_id)
    {
        $kegiatans = \App\Models\KegiatanMaster::where('indikator_id', $indikator_id)->get(['id', 'nama_kegiatan', 'tahapan_json']);
        return response()->json($kegiatans);
    }

    public function storeAktivitas(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string',
            'indikator_id' => 'required|exists:indikators,id',
            'kegiatan_id' => 'required|exists:kegiatan_masters,id',
            'triwulan' => 'required|integer|between:1,4',
            'tahapan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'uraian' => 'required|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,csv|max:10240',
        ]);

        $pegawai = Pegawai::where('nip', $validated['nip'])
                        ->orWhere('email_bps', $validated['nip'])
                        ->first();

        if (!$pegawai) {
            return redirect()->back()->withErrors(['nip' => 'NIP atau Email tidak terdaftar dalam database kami.'])->withInput();
        }

        $paths = [];
        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $paths[] = $file->store('lampiran', 'public');
            }
        }

        \App\Models\Aktivitas::create([
            'indikator_id' => $validated['indikator_id'],
            'kegiatan_id' => $validated['kegiatan_id'],
            'pegawai_nip' => $pegawai->nip ?? $pegawai->email_bps,
            'triwulan' => $validated['triwulan'],
            'tahapan' => $validated['tahapan'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'uraian' => $validated['uraian'],
            'lampiran' => $paths,
        ]);

        return redirect()->back()->with('success', 'Aktivitas berhasil dicatat.');
    }
}
