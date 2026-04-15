<?php

namespace App\Imports;

use App\Models\OutputMaster;
use App\Models\Indikator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OutputMasterImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $indikator = Indikator::where('kode', $row['kode_indikator'])->first();

        // Security/Validation: Skip if indicator not found
        if (!$indikator) {
            return null;
        }

        // Authorization: Non-admin can only import for their indicators
        if (!auth()->user()->isAdmin()) {
            if ($indikator->pic_id != auth()->user()->pegawai_id) {
                return null;
            }
        }

        return new OutputMaster([
            'indikator_id' => $indikator->id,
            'nama_output'  => $row['nama_output'],
            'jenis_output' => $row['jenis_output'] ?? 'Laporan',
            'periode'      => $row['periode'] ?? 'Triwulanan',
            'is_achieved'  => false,
        ]);
    }
}
