<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index()
    {
        $indikators = Indikator::with('target')->get();
        return view('target.index', compact('indikators'));
    }

    public function show($id)
    {
        $target = Target::firstOrCreate(['indikator_id' => $id]);
        return response()->json($target->load('indikator'));
    }

    public function update(Request $request, $id)
    {
        $target = Target::firstOrCreate(['indikator_id' => $id]);
        
        // Sanitasi input: ubah koma menjadi titik untuk desimal
        $data = $request->all();
        foreach(['target_tw1', 'target_tw2', 'target_tw3', 'target_tw4'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = str_replace(',', '.', $data[$field]);
            }
        }
        $request->merge($data);

        $target->update($request->validate([
            'target_tw1' => 'nullable|numeric',
            'target_tw2' => 'nullable|numeric',
            'target_tw3' => 'nullable|numeric',
            'target_tw4' => 'nullable|numeric',
        ]));

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Target berhasil diperbarui',
                'data' => $target
            ]);
        }

        return redirect()->route('target.index')->with('success', 'Target berhasil diperbarui');
    }
}
