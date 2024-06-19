<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WsmNormalization extends Model
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
    ];

    public function alatMaster()
    {
        return $this->belongsTo(AlatMaster::class, 'alat_master_id', 'id');
    }
}
