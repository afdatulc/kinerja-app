<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tujuan',
        'sasaran',
        'indikator_kinerja',
        'jenis_indikator',
        'periode',
        'tipe',
        'satuan',
        'target_tahunan',
        'tahun',
        'pic_id',
    ];

    public function pic()
    {
        return $this->belongsTo(Pegawai::class, 'pic_id');
    }

    public function target()
    {
        return $this->hasOne(Target::class);
    }

    public function realisasis()
    {
        return $this->hasMany(Realisasi::class);
    }

    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class);
    }

    public function kegiatanMasters()
    {
        return $this->hasMany(KegiatanMaster::class);
    }

    public function analisis()
    {
        return $this->hasMany(Analisis::class);
    }

    public function getCapaianTahunanAttribute()
    {
        $realisasiTerakhir = $this->realisasis()->orderBy('triwulan', 'desc')->first();
        if (!$realisasiTerakhir || $this->target_tahunan == 0) return 0;
        return ($realisasiTerakhir->realisasi_kumulatif / $this->target_tahunan) * 100;
    }

    public function getStatusWarnaAttribute()
    {
        $capaian = $this->capaian_tahunan;
        if ($capaian >= 100) return 'success';
        if ($capaian >= 80) return 'warning';
        return 'danger';
    }
}
