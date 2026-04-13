<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\Realisasi;
use App\Http\Requests\RealisasiRequest;
use Illuminate\Http\Request;

class RealisasiController extends Controller
{
    public function index(Request $request)
    {
        $triwulan = $request->get('triwulan', 1);
        $realisasis = Realisasi::with(['indikator.pic'])->where('triwulan', $triwulan)->get();
        return view('realisasi.index', compact('realisasis', 'triwulan'));
    }

    public function entry(Indikator $indikator)
    {
        return view('realisasi.entry', compact('indikator'));
    }

    public function getContext(Indikator $indikator, $triwulan)
    {
        $target = $indikator->target;
        $targetField = 'target_tw' . $triwulan;
        $targetVal = $target ? $target->$targetField : 0;

        $previousRealisasi = Realisasi::where('indikator_id', $indikator->id)
            ->where('triwulan', '<', $triwulan)
            ->orderBy('triwulan', 'desc')
            ->first();

        $currentRealisasi = Realisasi::where('indikator_id', $indikator->id)
            ->where('triwulan', $triwulan)
            ->first();

        $aktivitas = $indikator->aktivitas()
            ->where('triwulan', $triwulan)
            ->get();

        $aktivitasFormatted = $aktivitas->map(function($a) {
            $pegawai = \App\Models\Pegawai::where('nip', $a->pegawai_nip)->first();
            return [
                'pegawai' => $pegawai ? $pegawai->nama : $a->pegawai_nip,
                'uraian' => $a->uraian,
                'tahapan' => $a->tahapan,
                'tanggal' => $a->tanggal_mulai . ' - ' . $a->tanggal_selesai,
                'lampirans' => $a->lampiran ?? []
            ];
        });

        return response()->json([
            'target' => $targetVal,
            'previous_value' => $previousRealisasi ? $previousRealisasi->realisasi_kumulatif : 0,
            'current_value' => $currentRealisasi ? $currentRealisasi->realisasi_kumulatif : null,
            'aktivitas' => $aktivitasFormatted,
            'outputs' => $indikator->outputMasters
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'triwulan' => 'required|integer|between:1,4',
            'realisasi_kumulatif' => 'required|numeric',
        ]);

        // Validation TW > Previous TW
        $previous = Realisasi::where('indikator_id', $validated['indikator_id'])
            ->where('triwulan', '<', $validated['triwulan'])
            ->orderBy('triwulan', 'desc')
            ->first();

        if ($previous && $validated['realisasi_kumulatif'] < $previous->realisasi_kumulatif) {
            return redirect()->back()->withErrors([
                'realisasi_kumulatif' => "Nilai kumulatif tidak boleh lebih kecil dari Triwulan sebelumnya ({$previous->realisasi_kumulatif})"
            ])->withInput();
        }

        $realisasi = Realisasi::where('indikator_id', $validated['indikator_id'])
            ->where('triwulan', $validated['triwulan'])
            ->first();

        $action = $realisasi ? 'updated' : 'created';
        $oldValue = $realisasi ? $realisasi->realisasi_kumulatif : null;

        $realisasi = Realisasi::updateOrCreate(
            ['indikator_id' => $validated['indikator_id'], 'triwulan' => $validated['triwulan']],
            ['realisasi_kumulatif' => $validated['realisasi_kumulatif']]
        );

        // Record Log
        if ($oldValue != $validated['realisasi_kumulatif']) {
            \App\Models\RealisasiLog::create([
                'realisasi_id' => $realisasi->id,
                'user_id' => auth()->id(),
                'old_value' => $oldValue,
                'new_value' => $validated['realisasi_kumulatif'],
                'action' => $action
            ]);
        }

        return redirect()->route('rekap.capaian')->with('success', 'Realisasi berhasil disimpan');
    }

    public function history(Realisasi $realisasi)
    {
        // Only Admin
        if (!auth()->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $logs = $realisasi->logs()->with('user')->latest()->get();
        return response()->json($logs);
    }
}
