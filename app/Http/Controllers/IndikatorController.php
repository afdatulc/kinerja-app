<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Http\Requests\IndikatorRequest;
use App\Imports\IndikatorImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class IndikatorController extends Controller
{
    public function index()
    {
        $query = Indikator::with('pic')
            ->withCount([
                'kegiatanMasters',
                'outputMasters',
                'outputMasters as completed_outputs_count' => function ($q) {
                    $q->where('is_achieved', true);
                }
            ]);
        
        if (!auth()->user()->isAdmin()) {
            $pegawaiId = auth()->user()->pegawai_id;
            if ($pegawaiId) {
                // Only show indicators assigned to this user
                $query->where('pic_id', $pegawaiId);
            } else {
                // If user doesn't have a linked pegawai profile, show nothing
                $query->whereRaw('1 = 0');
            }
        }

        $indikators = $query->get();
        $pegawais = \App\Models\Pegawai::all();
        return view('indikator.index', compact('indikators', 'pegawais'));
    }

    public function store(IndikatorRequest $request)
    {
        $indikator = Indikator::create($request->validated());
        $indikator->target()->create();
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Indikator berhasil ditambahkan',
                'data' => $indikator
            ]);
        }
        
        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil ditambahkan');
    }

    public function show(Indikator $indikator)
    {
        return response()->json($indikator);
    }

    public function update(IndikatorRequest $request, Indikator $indikator)
    {
        $indikator->update($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Indikator berhasil diperbarui',
                'data' => $indikator
            ]);
        }
        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil diperbarui');
    }

    public function updateTautan(Request $request, Indikator $indikator)
    {
        $validated = $request->validate([
            'dasar_hitung' => 'nullable|string',
            'link_bukti_kinerja' => 'nullable|string',
            'link_bukti_tindak_lanjut' => 'nullable|string',
            'penjelasan_lainnya' => 'nullable|string',
        ]);

        // Convert empty strings to null for URL fields
        $validated['link_bukti_kinerja'] = !empty($validated['link_bukti_kinerja']) ? $validated['link_bukti_kinerja'] : null;
        $validated['link_bukti_tindak_lanjut'] = !empty($validated['link_bukti_tindak_lanjut']) ? $validated['link_bukti_tindak_lanjut'] : null;

        $indikator->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Dasar Hitung & Tautan berhasil diperbarui',
                'data' => $indikator
            ]);
        }

        return redirect()->route('indikator.index')->with('success', 'Dasar Hitung & Tautan berhasil diperbarui');
    }

    public function destroy(Indikator $indikator)
    {
        $indikator->delete();

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Indikator berhasil dihapus'
            ]);
        }

        return redirect()->route('indikator.index')->with('success', 'Indikator berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new IndikatorImport, $request->file('file'));

        return redirect()->route('indikator.index')->with('success', 'Data Indikator berhasil diimport.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'kode', 'tujuan', 'sasaran', 'indikator_kinerja', 
            'jenis_indikator', 'periode', 'tipe', 'satuan', 
            'target_tahunan', 'tahun'
        ];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array { return [$this->headers]; }
        }, 'template_import_indikator.xlsx');
    }
}
