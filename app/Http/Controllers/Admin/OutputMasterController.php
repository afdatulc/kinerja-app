<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OutputMaster;
use App\Models\Indikator;
use Illuminate\Http\Request;

class OutputMasterController extends Controller
{
    public function index()
    {
        $query = OutputMaster::with('indikator');
        
        // Filter: Admin sees all, PIC Indikator sees outputs under their indicators
        if (!auth()->user()->isAdmin()) {
            $query->whereHas('indikator', function($q) {
                $q->where('pic_id', auth()->user()->pegawai_id);
            });
        }

        $outputs = $query->get();
        $indikators = Indikator::all();
        
        return view('admin.output.index', compact('outputs', 'indikators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'nama_output' => 'required|string',
            'jenis_output' => 'required|in:Laporan,Publikasi',
            'periode' => 'required|in:Tahunan,Triwulanan,Bulanan',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('outputs', 'public');
        }

        $output = OutputMaster::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Master Output berhasil ditambahkan',
                'data' => $output->load('indikator'),
                'file_url' => $output->file_path ? asset('storage/' . $output->file_path) : null
            ]);
        }

        return redirect()->route('output-master.index')->with('success', 'Master Output berhasil ditambahkan.');
    }

    public function show(OutputMaster $outputMaster)
    {
        return response()->json(array_merge(
            $outputMaster->load('indikator')->toArray(),
            ['file_url' => $outputMaster->file_path ? asset('storage/' . $outputMaster->file_path) : null]
        ));
    }

    public function update(Request $request, OutputMaster $outputMaster)
    {
        $validated = $request->validate([
            'indikator_id' => 'required|exists:indikators,id',
            'nama_output' => 'required|string',
            'jenis_output' => 'required|in:Laporan,Publikasi',
            'periode' => 'required|in:Tahunan,Triwulanan,Bulanan',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($outputMaster->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($outputMaster->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($outputMaster->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('outputs', 'public');
        }

        $outputMaster->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Master Output berhasil diperbarui',
                'data' => $outputMaster->load('indikator'),
                'file_url' => $outputMaster->file_path ? asset('storage/' . $outputMaster->file_path) : null
            ]);
        }

        return redirect()->route('output-master.index')->with('success', 'Master Output berhasil diperbarui.');
    }

    public function destroy(OutputMaster $outputMaster)
    {
        $outputMaster->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Master Output berhasil dihapus'
            ]);
        }

        return redirect()->route('output-master.index')->with('success', 'Master Output berhasil dihapus.');
    }

    public function toggleStatus(OutputMaster $outputMaster)
    {
        $outputMaster->update([
            'is_achieved' => !$outputMaster->is_achieved
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status output berhasil diperbarui',
            'is_achieved' => $outputMaster->is_achieved
        ]);
    }

    public function uploadFile(Request $request, OutputMaster $outputMaster)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($outputMaster->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($outputMaster->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($outputMaster->file_path);
            }

            $path = $request->file('file')->store('outputs', 'public');
            
            $outputMaster->update([
                'file_path' => $path
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Dokumen berhasil diunggah',
                'file_path' => $path,
                'file_url' => asset('storage/' . $path),
                'file_name' => basename($path)
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
    }
}
