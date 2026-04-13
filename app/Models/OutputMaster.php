<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'indikator_id',
        'nama_output',
        'jenis_output',
        'periode',
        'is_achieved',
        'file_path',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class);
    }
}
