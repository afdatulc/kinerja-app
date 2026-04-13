<?php

namespace App\Http\Controllers;

use App\Models\Indikator;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $user = auth()->user();
        
        $indikators = Indikator::where('tahun', $tahun)->with(['target', 'realisasis'])->get();

        if ($user->isAdmin()) {
            $summary = [
                'total' => $indikators->count(),
                'hijau' => $indikators->filter(fn($i) => $i->status_warna == 'success')->count(),
                'kuning' => $indikators->filter(fn($i) => $i->status_warna == 'warning')->count(),
                'merah' => $indikators->filter(fn($i) => $i->status_warna == 'danger')->count(),
            ];
            
            return view('dashboard', compact('indikators', 'summary', 'tahun'));
        } else {
            // Pegawai View
            $pegawai = $user->pegawai;
            if (!$pegawai) {
                return view('dashboard', [
                    'indikators' => collect(),
                    'summary' => ['total' => 0, 'hijau' => 0, 'kuning' => 0, 'merah' => 0],
                    'tahun' => $tahun,
                    'error' => 'Data pegawai tidak ditemukan untuk user ini.'
                ]);
            }

            $myActivitiesCount = \App\Models\Aktivitas::where('pegawai_nip', $pegawai->nip)->count();
            $myIndicators = Indikator::where('pic_id', $pegawai->id)
                ->where('tahun', $tahun)
                ->with(['target', 'realisasis'])
                ->get();
            
            $summary = [
                'personal_activities' => $myActivitiesCount,
                'total_pic' => $myIndicators->count(),
                'pic_hijau' => $myIndicators->filter(fn($i) => $i->status_warna == 'success')->count(),
                'pic_critical' => $myIndicators->filter(fn($i) => $i->status_warna == 'danger')->count(),
            ];

            return view('dashboard_pegawai', [
                'indikators' => $myIndicators,
                'summary' => $summary,
                'tahun' => $tahun,
                'pegawai' => $pegawai
            ]);
        }
    }
}
