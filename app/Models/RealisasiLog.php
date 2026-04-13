<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealisasiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'realisasi_id',
        'user_id',
        'old_value',
        'new_value',
        'action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function realisasi()
    {
        return $this->belongsTo(Realisasi::class);
    }
}
