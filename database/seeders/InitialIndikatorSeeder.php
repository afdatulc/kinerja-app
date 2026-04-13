<?php

namespace Database\Seeders;

use App\Models\Indikator;
use App\Models\Target;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InitialIndikatorSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.1.1 - Terwujudnya Penyediaan Data...", "1.1.1.1 - Persentase Publikasi/Laporan Statistik Kependudukan...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.1.3 - Terwujudnya Penyediaan Data...", "1.1.3.1 - Persentase Publikasi/Laporan Statistik Kesejahteraan...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.1.5 - Terwujudnya penyediaan Data...", "1.1.5.1 - Persentase Publikasi/Laporan Statistik Ketahanan Sosial...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.2.1 - Terwujudnya Penyediaan Data...", "1.2.1.1 - Persentase Publikasi/Laporan Statistik Sumber Daya Hayati...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.2.2 - Terwujudnya Penyediaan Data...", "1.2.2.1 - Persentase publikasi/laporan Statistik Sumber Daya Mineral...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.2.3 - Terwujudnya penyediaan Data...", "1.2.3.1 - Persentase publikasi/laporan Statistik Industri...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.3.1 - Terwujudnya Penyediaan Data...", "1.3.1.1 - Persentase Publikasi/Laporan Statistik Distribusi...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.3.3 - Terwujudnya Penyediaan Data...", "1.3.3.1 - Persentase Publikasi/laporan Statistik Harga...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.3.5 - Terwujudnya Penyediaan Data...", "1.3.5.1 - Persentase Publikasi/Laporan Statistik Jasa...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.4.1 - Terwujudnya Penyediaan Data...", "1.4.1.1 - Persentase Publikasi/Laporan Neraca Produksi...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.4.1 - Terwujudnya Penyediaan Data...", "1.4.1.2 - Persentase Publikasi/Laporan Neraca Pengeluaran...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T1: Mewujudkan Perumusan Kebijakan...", "1.4.1 - Terwujudnya Penyediaan Data...", "1.4.1.3 - Persentase Publikasi/Laporan Analisis Statistik...", "IKU", "Tahunan", "Persen", 100, 0, 0, 0, 100],
            ["T2: Mewujudkan Penyelenggaraan Sistem...", "2.1.4 - Terwujudnya Kapasitas Tata Kelola...", "2.1.4.1 - Persentase Kumulatif Desa Yang Berpredikat Desa Cinta Statistik", "IKU", "Tahunan", "Persen", 4.44, 2.22, 2.22, 2.22, 4.44],
            ["T2: Mewujudkan Penyelenggaraan Sistem...", "2.5.1 - Terwujudnya Penguatan Penyelenggaraan Pembinaan...", "2.5.1.1 - Tingkat Penyelenggaraan Pembinaan Statistik Sektoral...", "IKU", "Triwulanan", "Persen", 100, 19.2, 55.64, 71.7, 100],
            ["T2: Mewujudkan Penyelenggaraan Sistem...", "2.7.1 - Terwujudnya Kemudahan Akses Data...", "2.7.1.1 - Indeks Pelayanan Publik - Penilaian mandiri", "IKU", "Triwulanan", "Poin", 4.46, 0, 1.07, 4.15, 4.46],
            ["T3: Mewujudkan Tata Kelola Badan Pusat Statistik...", "3.2.4 - Tersedianya Dukungan Manajemen...", "3.2.4.1 - Nilai SAKIP oleh Inspektorat", "IKU", "Tahunan", "Poin", 74.75, 0, 0, 0, 74.75],
            ["T3: Mewujudkan Tata Kelola Badan Pusat Statistik...", "3.2.4 - Tersedianya Dukungan Manajemen...", "3.2.4.2 - Indeks Implementasi BerAKHLAK", "IKU", "Tahunan", "Persen", 73, 0, 0, 0, 73],
        ];

        // Mapping full descriptions for the seeder
        $realData = [
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.1.1 - Terwujudnya Penyediaan Data dan Insight Statistik Kependudukan dan Ketenagakerjaan yang Berkualitas",
                "indikator" => "1.1.1.1 - Persentase Publikasi/Laporan Statistik Kependudukan dan Ketenagakerjaan yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.1.3 - Terwujudnya Penyediaan Data dan Insight Statistik Kesejahteraan Rakyat yang Berkualitas",
                "indikator" => "1.1.3.1 - Persentase Publikasi/Laporan Statistik Kesejahteraan Rakyat yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.1.5 - Terwujudnya penyediaan Data dan Insight Statistik Ketahanan Sosial yang Berkualitas",
                "indikator" => "1.1.5.1 - Persentase Publikasi/Laporan Statistik Ketahanan Sosial yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.2.1 - Terwujudnya Penyediaan Data dan Insight Statistik Sumber Daya Hayati yang Berkualitas",
                "indikator" => "1.2.1.1 - Persentase Publikasi/Laporan Statistik Sumber Daya Hayati yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.2.2 - Terwujudnya Penyediaan Data dan Insight Statistik Sumber Daya Mineral dan Konstruksi yang Berkualitas",
                "indikator" => "1.2.2.1 - Persentase publikasi/laporan Statistik Sumber Daya Mineral dan Konstruksi yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.2.3 - Terwujudnya penyediaan Data dan Insight Statistik Industri yang Berkualitas",
                "indikator" => "1.2.3.1 - Persentase publikasi/laporan Statistik Industri yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.3.1 - Terwujudnya Penyediaan Data dan Insight Statistik Distribusi yang Berkualitas",
                "indikator" => "1.3.1.1 - Persentase Publikasi/Laporan Statistik Distribusi yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.3.3 - Terwujudnya Penyediaan Data dan Insight Statistik Harga yang Berkualitas",
                "indikator" => "1.3.3.1 - Persentase Publikasi/laporan Statistik Harga yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.3.5 - Terwujudnya Penyediaan Data dan Insight Statistik Jasa yang Berkualitas",
                "indikator" => "1.3.5.1 - Persentase Publikasi/Laporan Statistik Jasa yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.4.1 - Terwujudnya Penyediaan Data dan Insight Statistik Lintas Sektor yang Berkualitas",
                "indikator" => "1.4.1.1 - Persentase Publikasi/Laporan Neraca Produksi yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.4.1 - Terwujudnya Penyediaan Data dan Insight Statistik Lintas Sektor yang Berkualitas",
                "indikator" => "1.4.1.2 - Persentase Publikasi/Laporan Neraca Pengeluaran yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T1: Mewujudkan Perumusan Kebijakan dan Pengambilan Keputusan Berbasis Data Statistik Berkualitas dan Insight yang Relevan",
                "sasaran" => "1.4.1 - Terwujudnya Penyediaan Data dan Insight Statistik Lintas Sektor yang Berkualitas",
                "indikator" => "1.4.1.3 - Persentase Publikasi/Laporan Analisis Statistik dan Neraca Satelit yang Berkualitas",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 100, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 100
            ],
            [
                "tujuan" => "T2: Mewujudkan Penyelenggaraan Sistem Statistik Nasional yang Andal, Efektif, dan Efisien",
                "sasaran" => "2.1.4 - Terwujudnya Kapasitas Tata Kelola Pemerintah Desa Untuk Menghasilkan Statistik Berkualitas",
                "indikator" => "2.1.4.1 - Persentase Kumulatif Desa Yang Berpredikat Desa Cinta Statistik",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 4.44, "tw1" => 2.22, "tw2" => 2.22, "tw3" => 2.22, "tw4" => 4.44
            ],
            [
                "tujuan" => "T2: Mewujudkan Penyelenggaraan Sistem Statistik Nasional yang Andal, Efektif, dan Efisien",
                "sasaran" => "2.5.1 - Terwujudnya Penguatan Penyelenggaraan Pembinaan Statistik Sektoral Kementerian/Lembaga/Pemerintah Daerah",
                "indikator" => "2.5.1.1 - Tingkat Penyelenggaraan Pembinaan Statistik Sektoral sesuai standar",
                "jenis" => "IKU", "periode" => "Triwulanan", "satuan" => "Persen", "target" => 100, "tw1" => 19.2, "tw2" => 55.64, "tw3" => 71.7, "tw4" => 100
            ],
            [
                "tujuan" => "T2: Mewujudkan Penyelenggaraan Sistem Statistik Nasional yang Andal, Efektif, dan Efisien",
                "sasaran" => "2.7.1 - Terwujudnya Kemudahan Akses Data BPS",
                "indikator" => "2.7.1.1 - Indeks Pelayanan Publik - Penilaian mandiri",
                "jenis" => "IKU", "periode" => "Triwulanan", "satuan" => "Poin", "target" => 4.46, "tw1" => 0, "tw2" => 1.07, "tw3" => 4.15, "tw4" => 4.46
            ],
            [
                "tujuan" => "T3: Mewujudkan Tata Kelola Badan Pusat Statistik yang Berkualitas, Akuntabel, Efektif, dan Efisien dalam Menyelenggarakan Statistik",
                "sasaran" => "3.2.4 - Tersedianya Dukungan Manajemen pada BPS Provinsi dan BPS Kabupaten/Kota",
                "indikator" => "3.2.4.1 - Nilai SAKIP oleh Inspektorat",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Poin", "target" => 74.75, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 74.75
            ],
            [
                "tujuan" => "T3: Mewujudkan Tata Kelola Badan Pusat Statistik yang Berkualitas, Akuntabel, Efektif, dan Efisien dalam Menyelenggarakan Statistik",
                "sasaran" => "3.2.4 - Tersedianya Dukungan Manajemen pada BPS Provinsi dan BPS Kabupaten/Kota",
                "indikator" => "3.2.4.2 - Indeks Implementasi BerAKHLAK",
                "jenis" => "IKU", "periode" => "Tahunan", "satuan" => "Persen", "target" => 73, "tw1" => 0, "tw2" => 0, "tw3" => 0, "tw4" => 73
            ],
        ];

        foreach ($realData as $item) {
            // Extract Kode from Indikator string
            $parts = explode(' - ', $item['indikator'], 2);
            $kode = trim($parts[0]);
            $namaIndikator = trim($parts[1] ?? $parts[0]);

            $indikator = Indikator::create([
                'kode'              => $kode,
                'tujuan'            => $item['tujuan'],
                'sasaran'           => $item['sasaran'],
                'indikator_kinerja' => $namaIndikator,
                'jenis_indikator'   => $item['jenis'],
                'periode'           => $item['periode'] == 'Triwulanan' ? 'Triwulanan' : 'Tahunan',
                'tipe'              => $item['satuan'] == 'Persen' ? 'Persen' : 'Non Persen',
                'satuan'            => $item['satuan'],
                'target_tahunan'    => $item['target'],
                'tahun'             => 2026,
            ]);

            Target::create([
                'indikator_id' => $indikator->id,
                'target_tw1'   => $item['tw1'],
                'target_tw2'   => $item['tw2'],
                'target_tw3'   => $item['tw3'],
                'target_tw4'   => $item['tw4'],
            ]);
        }
    }
}
