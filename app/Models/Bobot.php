<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bobot extends Model
{
    use HasFactory;

    protected $table = 'bobot';

    protected $primaryKey = 'id';

    protected $fillable = [
        'kriteria_id',
        'bobot',
        'user_id',
        'rand_token',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
