<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TitikPerekaman extends Model
{
    protected $table = 'titik_perekamans';

    protected $fillable = [
        'koordinat_gps',
        'lokasi_spesifik',
        'elevasi_mdpl',
    ];

    public function rekaman(): HasOne
    {
        return $this->hasOne(Rekaman::class);
    }
}
