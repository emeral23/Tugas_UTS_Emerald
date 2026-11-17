<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Habitat extends Model
{
    protected $fillable = [
        'kode_habitat',
        'nama_habitat',
        'deskripsi',
    ];

    public function rekamans(): HasMany
    {
        return $this->hasMany(Rekaman::class);
    }
}
