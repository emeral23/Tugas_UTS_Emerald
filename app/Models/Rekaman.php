<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rekaman extends Model
{
    // Tambahkan baris ini agar Eloquent mengarah ke tabel 'rekamans' dengan benar
    protected $table = 'rekamans';

    protected $fillable = [
        'habitat_id',
        'titik_perekaman_id',
        'kode_rekaman',
        'nama_rekaman',
        'tanggal_perekaman',
        'perekam',
        'status_validasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_perekaman' => 'date',
            'status_validasi' => 'boolean',
        ];
    }

    public function habitat(): BelongsTo
    {
        return $this->belongsTo(Habitat::class);
    }

    public function titikPerekaman(): BelongsTo
    {
        return $this->belongsTo(TitikPerekaman::class);
    }

    public function anotasis(): BelongsToMany
    {
        return $this->belongsToMany(Anotasi::class, 'anotasi_rekaman')
                    ->withPivot('frekuensi_dominan')
                    ->withTimestamps();
    }
}
