<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        Pegawai::create([
            'nip' => '199001012015011001',
            'nama' => 'Budi Santoso',
            'jabatan' => 'Statistisi Ahli Pertama',
            'unit_kerja' => 'BPS Kabupaten Banjar',
        ]);

        Pegawai::create([
            'nip' => '199205052017052002',
            'nama' => 'Siti Aminah',
            'jabatan' => 'Pranata Komputer Mahir',
            'unit_kerja' => 'BPS Kabupaten Tapin',
        ]);
    }
}
