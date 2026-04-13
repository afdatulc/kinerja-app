<?php

namespace App\Http\Controllers;

use App\Exports\RealisasiExport;
use App\Exports\IndikatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function realisasi()
    {
        return Excel::download(new RealisasiExport, 'realisasi_kinerja_' . date('Ymd_His') . '.xlsx');
    }

    public function indikator()
    {
        return Excel::download(new IndikatorExport, 'master_indikator_' . date('Ymd_His') . '.xlsx');
    }
}
