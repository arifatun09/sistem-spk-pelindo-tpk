<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anhipro extends Model
{
    use HasFactory;

    protected $fillable = [
        'kriteria_id',
        'hasil',
    ];

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id', 'id');
    }
}
