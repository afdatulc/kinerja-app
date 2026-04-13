<?php

namespace App\Exports;

use App\Models\Indikator;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CapaianExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $indicators = Indikator::with(['target', 'realisasis', 'kegiatanMasters', 'analisis'])
            ->where('tahun', $this->tahun)
            ->get();

        $grouped = $indicators->groupBy('tujuan')->map(function ($itemsByTujuan) {
            return $itemsByTujuan->groupBy('sasaran');
        });

        return view('exports.rekap_capaian', [
            'grouped' => $grouped,
            'tahun' => $this->tahun,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1    => ['font' => ['bold' => true]],
        ];
    }
}
