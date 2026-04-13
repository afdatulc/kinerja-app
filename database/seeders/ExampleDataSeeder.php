<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indikator = \App\Models\Indikator::create([
            'tujuan' => 'Meningkatkan Kualitas Data Statistik',
            'sasaran' => 'Tersedianya Data Statistik Sektoral yang Berkualitas',
            'indikator_kinerja' => 'Persentase Rekomendasi Statistik yang ditindaklanjuti',
            'jenis_indikator' => 'IKU',
            'periode' => 'Triwulanan',
            'tipe' => 'Persen',
            'satuan' => '%',
            'target_tahunan' => 100,
            'tahun' => 2026,
        ]);

        \App\Models\Target::create([
            'indikator_id' => $indikator->id,
            'target_tw1' => 25,
            'target_tw2' => 50,
            'target_tw3' => 75,
            'target_tw4' => 100,
        ]);

        \App\Models\Realisasi::create([
            'indikator_id' => $indikator->id,
            'triwulan' => 1,
            'realisasi_kumulatif' => 24.5,
        ]);
        
        \App\Models\Analisis::create([
            'indikator_id' => $indikator->id,
            'triwulan' => 1,
            'kendala' => 'Beberapa OPD belum mengumpulkan dokumen.',
            'solusi' => 'Melakukan jemput bola ke instansi terkait.',
            'rencana_tindak_lanjut' => 'Mengirimkan surat teguran kedua.',
            'pic_tindak_lanjut' => 'Bidang IPDS',
            'batas_waktu' => '2026-04-30',
        ]);
    }
}
