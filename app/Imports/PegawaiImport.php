<?php

namespace App\Imports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $nip = isset($row['nip']) && !empty(trim($row['nip'])) ? trim($row['nip']) : null;
        
        return new Pegawai([
            'nip'        => $nip,
            'nama'       => $row['nama'],
            'email_bps'  => $row['email_bps'] ?? null,
            'jabatan'    => $row['jabatan'],
            'unit_kerja' => $row['unit_kerja'],
            'status'     => $row['status'] ?? 'PNS',
            'seksi'      => $row['seksi'] ?? 'Lainnya',
            'no_hp'      => $row['no_hp'] ?? null,
        ]);
    }
}
