<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputRealisasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'output_master_id',
        'triwulan',
        'volume',
        'progres',
    ];

    public function outputMaster()
    {
        return $this->belongsTo(OutputMaster::class, 'output_master_id');
    }
}
