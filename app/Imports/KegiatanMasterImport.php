<?php

namespace App\Imports;

use App\Models\KegiatanMaster;
use App\Models\Indikator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KegiatanMasterImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $indikator = Indikator::where('kode', $row['kode_indikator'])->first();

        if (!$indikator) {
            return null;
        }

        $tahapanRaw = $row['tahapan'] ?? 'Persiapan';
        $tahapanArray = array_map('trim', explode(',', $tahapanRaw));

        return new KegiatanMaster([
            'indikator_id'   => $indikator->id,
            'nama_kegiatan'  => $row['nama_kegiatan'],
            'tahapan_json'   => $tahapanArray,
        ]);
    }
}
