<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmResultNormalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'alat_master_id',
        'utilisasi',
        'availability',
        'reliability',
        'idle',
        'jam_tersedia',
        'jam_operasi',
        'jam_bda',
        'jumlah_bda',
        'hasil',
        'rangking',
    ];

    public function alatMaster()
    {
        return $this->belongsTo(AlatMaster::class, 'alat_master_id', 'id');
    }
}
