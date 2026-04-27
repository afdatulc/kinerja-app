<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index()
    {
        $triwulan = request('triwulan');
        $user = auth()->user();
        
        $query = Aktivitas::with(['indikator', 'pegawai']);
        
        // Role based filtering
        if ($user->isPegawai()) {
            if ($user->pegawai) {
                $query->where('pegawai_nip', $user->pegawai->nip);
            } else {
                // If pegawai profile is missing, don't show any activities
                $query->whereRaw('1 = 0');
            }
        }

        if ($triwulan) {
            $query->where('triwulan', $triwulan);
        }
        
        $aktivitas = $query->latest()->get();
        
        return view('admin.aktivitas.index', compact('aktivitas'));
    }

    public function edit(Aktivitas $aktivitas)
    {
        // Auth check
        if (auth()->user()->isPegawai()) {
            $pegawai = auth()->user()->pegawai;
            if (!$pegawai || $aktivitas->pegawai_nip !== $pegawai->nip) {
                abort(403);
            }
        }

        return view('admin.aktivitas.edit', compact('aktivitas'));
    }

    public function update(Request $request, Aktivitas $aktivitas)
    {
        // Auth check
        if (auth()->user()->isPegawai()) {
            $pegawai = auth()->user()->pegawai;
            if (!$pegawai || $aktivitas->pegawai_nip !== $pegawai->nip) {
                abort(403);
            }
        }

        $request->validate([
            'uraian' => 'required',
            'tahapan' => 'required',
            'lampiran.*' => 'nullable|file|max:10240',
        ]);

        $lampirans = $aktivitas->lampiran ?? [];

        // Handle deletion of existing attachments
        if ($request->has('deleted_lampiran')) {
            $deleted = $request->deleted_lampiran;
            $lampirans = array_filter($lampirans, function($path) use ($deleted) {
                return !in_array($path, $deleted);
            });
            // Re-index array
            $lampirans = array_values($lampirans);
        }

        if ($request->hasFile('lampiran')) {
            foreach ($request->file('lampiran') as $file) {
                $path = $file->store('aktivitas', 'public');
                $lampirans[] = $path;
            }
        }

        $aktivitas->update([
            'uraian' => $request->uraian,
            'tahapan' => $request->tahapan,
            'lampiran' => $lampirans,
        ]);

        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Aktivitas $aktivitas)
    {
        $aktivitas->delete();
        return redirect()->back()->with('success', 'Aktivitas berhasil dihapus.');
    }
}
