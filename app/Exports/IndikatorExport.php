<?php

namespace App\Exports;

use App\Models\Indikator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IndikatorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Indikator::select('id', 'tujuan', 'sasaran', 'indikator_kinerja', 'jenis_indikator', 'satuan', 'target_tahunan', 'tahun')->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Tujuan', 'Sasaran', 'Indikator Kinerja', 
            'Jenis Indikator', 'Satuan', 'Target Tahunan', 'Tahun'
        ];
    }
}
