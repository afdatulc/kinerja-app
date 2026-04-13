<?php

namespace App\Imports;

use App\Models\Indikator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IndikatorImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Indikator([
            'kode'              => $row['kode'],
            'tujuan'            => $row['tujuan'],
            'sasaran'           => $row['sasaran'],
            'indikator_kinerja' => $row['indikator_kinerja'],
            'jenis_indikator'   => $row['jenis_indikator'],
            'periode'           => $row['periode'],
            'tipe'              => $row['tipe'],
            'satuan'            => $row['satuan'],
            'target_tahunan'    => $row['target_tahunan'] ?? 0,
            'tahun'             => $row['tahun'] ?? 2026,
        ]);
    }
}
