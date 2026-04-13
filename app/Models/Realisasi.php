<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator_id', 'triwulan', 'realisasi_kumulatif'
    ];

    public function realisasis()
    {
        return $this->hasMany(RealisasiLog::class);
    }

    public function logs()
    {
        return $this->hasMany(RealisasiLog::class);
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }

    public function getCapaianTriwulanAttribute()
    {
        $target = $this->indikator->target;
        $targetField = 'target_tw' . $this->triwulan;
        $targetVal = $target ? $target->$targetField : 0;
        
        if ($targetVal == 0) return 0;
        return ($this->realisasi_kumulatif / $targetVal) * 100;
    }
}
