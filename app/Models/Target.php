<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator_id', 'target_tw1', 'target_tw2', 'target_tw3', 'target_tw4'
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }
}
