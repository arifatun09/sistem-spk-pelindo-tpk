<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatMaster extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'alat_masters';
    protected $fillable = [
        'id',
        'kode',
        'nama',
    ];

    /**Relasi one-to-many ke model Alat**/
    public function alats()
    {
        return $this->hasMany(Alat::class, 'alat_master_id', 'id');
    }
}
