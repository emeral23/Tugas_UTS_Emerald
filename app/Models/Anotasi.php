<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Anotasi extends Model
{
    protected $fillable = [
        'kode_anotasi',
        'jenis_suara',
        'keterangan',
    ];

    public function rekamans(): BelongsToMany
    {
        return $this->belongsToMany(Rekaman::class, 'anotasi_rekaman')
                    ->withPivot('frekuensi_dominan')
                    ->withTimestamps();
    }
}
