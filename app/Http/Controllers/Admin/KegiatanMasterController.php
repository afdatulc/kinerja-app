<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KegiatanMaster;
use App\Models\Indikator;
use App\Imports\KegiatanMasterImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class KegiatanMasterController extends Controller
{
    public function index()
    {
        $query = KegiatanMaster::with(['indikator', 'ketuaTim', 'anggotas']);
        
        // Filter: Admin sees all, PIC Indikator sees activities under their indicators
        if (!auth()->user()->isAdmin()) {
            $query->whereHas('indikator', function($q) {
                $q->where('pic_id', auth()->user()->pegawai_id);
            })->orWhere('ketua_tim_id', auth()->user()->pegawai_id)
              ->orWhereHas('anggotas', function($q) {
                  $q->where('pegawai_id', auth()->user()->pegawai_id);
              });
        }

        $kegiatans = $query->get();
        $indikators = Indikator::all();
        $pegawais = \App\Models\Pegawai::all();
        
        return view('admin.kegiatan.index', compact('kegiatans', 'indikators', 'pegawais'));
    }

    public function create()
    {
        $indikators = Indikator::all();
        return view('admin.kegiatan.create', compact('indikators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'nama_kegiatan' => 'required|string',
            'tahapan' => 'required|array',
        ]);

        $kegiatan = KegiatanMaster::create([
            'indikator_id' => $validated['indikator_id'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tahapan_json' => $validated['tahapan'],
            'ketua_tim_id' => $request->ketua_tim_id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kegiatan Master berhasil ditambahkan',
                'data' => $kegiatan
            ]);
        }

        return redirect()->route('kegiatan-master.index')->with('success', 'Kegiatan Master berhasil ditambahkan.');
    }

    public function show(KegiatanMaster $kegiatanMaster)
    {
        return response()->json($kegiatanMaster->load(['indikator', 'anggotas']));
    }

    public function update(Request $request, KegiatanMaster $kegiatanMaster)
    {
        $validated = $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'nama_kegiatan' => 'required|string',
            'tahapan' => 'required|array',
        ]);

        $kegiatanMaster->update([
            'indikator_id' => $validated['indikator_id'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tahapan_json' => $validated['tahapan'],
            'ketua_tim_id' => $request->ketua_tim_id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kegiatan Master berhasil diperbarui',
                'data' => $kegiatanMaster
            ]);
        }

        return redirect()->route('kegiatan-master.index')->with('success', 'Kegiatan Master berhasil diperbarui.');
    }

    public function destroy(KegiatanMaster $kegiatanMaster)
    {
        $kegiatanMaster->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kegiatan Master berhasil dihapus'
            ]);
        }

        return redirect()->route('kegiatan-master.index')->with('success', 'Kegiatan Master berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new KegiatanMasterImport, $request->file('file'));
        return redirect()->route('kegiatan-master.index')->with('success', 'Data Kegiatan berhasil diimport.');
    }

    public function downloadTemplate()
    {
        $headers = ['kode_indikator', 'nama_kegiatan', 'tahapan'];
        $example = ['1.1.1.1', 'Survei Angkatan Kerja Nasional', 'Persiapan, Pengumpulan Data, Pengolahan, Analisis'];
        
        return Excel::download(new class($headers, $example) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $headers;
            protected $example;
            public function __construct($headers, $example) { 
                $this->headers = $headers; 
                $this->example = $example; 
            }
            public function array(): array { 
                return [$this->headers, $this->example]; 
            }
        }, 'template_import_kegiatan.xlsx');
    }
    public function syncAnggota(Request $request, KegiatanMaster $kegiatanMaster)
    {
        $request->validate([
            'anggotas' => 'array',
            'anggotas.*' => 'exists:pegawais,id'
        ]);

        $kegiatanMaster->anggotas()->sync($request->anggotas);

        return response()->json([
            'status' => 'success',
            'message' => 'Anggota tim berhasil diperbarui',
            'data' => $kegiatanMaster->load('anggotas')
        ]);
    }
}
