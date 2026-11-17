<?php

namespace App\Services;

use App\Models\Rekaman;
use App\Models\TitikPerekaman;
use Illuminate\Support\Facades\DB;

class RekamanService
{
    /**
     * Menyimpan data rekaman baru beserta detail titik perekaman dan anotasi (Many-to-Many)
     * menggunakan Database Transaction agar atomik (materi pertemuan 4).
     */
    public function storeData(array $validatedData): Rekaman
    {
        return DB::transaction(function () use ($validatedData) {
            // 1. Buat data detail titik perekaman terlebih dahulu (Relasi 1-to-1)
            $titikPerekaman = TitikPerekaman::create([
                'koordinat_gps' => $validatedData['koordinat_gps'],
                'lokasi_spesifik' => $validatedData['lokasi_spesifik'],
                'elevasi_mdpl' => $validatedData['elevasi_mdpl'] ?? null,
            ]);

            // 2. Buat data utama rekaman dengan menyertakan foreign key titik_perekaman_id
            $rekaman = Rekaman::create([
                'habitat_id' => $validatedData['habitat_id'],
                'titik_perekaman_id' => $titikPerekaman->id,
                'kode_rekaman' => $validatedData['kode_rekaman'],
                'nama_rekaman' => $validatedData['nama_rekaman'],
                'tanggal_perekaman' => $validatedData['tanggal_perekaman'],
                'perekam' => $validatedData['perekam'],
                'status_validasi' => $validatedData['status_validasi'] ?? false,
            ]);

            // 3. Hubungkan pilihan jamak anotasi (Many-to-Many) melalui tabel pivot menggunakan attach/sync
            if (!empty($validatedData['anotasi_ids'])) {
                $pivotData = [];
                foreach ($validatedData['anotasi_ids'] as $anotasiId) {
                    $pivotData[$anotasiId] = [
                        'frekuensi_dominan' => $validatedData['frekuensi_dominan'][$anotasiId] ?? null,
                    ];
                }
                $rekaman->anotasis()->attach($pivotData);
            }

            return $rekaman;
        });
    }

    /**
     * Memperbarui data rekaman, titik perekaman, dan relasi anotasi.
     */
    public function updateData(Rekaman $rekaman, array $validatedData): Rekaman
    {
        return DB::transaction(function () use ($rekaman, $validatedData) {
            // 1. Update data titik perekaman terkait
            $rekaman->titikPerekaman()->update([
                'koordinat_gps' => $validatedData['koordinat_gps'],
                'lokasi_spesifik' => $validatedData['lokasi_spesifik'],
                'elevasi_mdpl' => $validatedData['elevasi_mdpl'] ?? null,
            ]);

            // 2. Update data utama rekaman
            $rekaman->update([
                'habitat_id' => $validatedData['habitat_id'],
                'kode_rekaman' => $validatedData['kode_rekaman'],
                'nama_rekaman' => $validatedData['nama_rekaman'],
                'tanggal_perekaman' => $validatedData['tanggal_perekaman'],
                'perekam' => $validatedData['perekam'],
                'status_validasi' => $validatedData['status_validasi'] ?? false,
            ]);

            // 3. Sinkronisasi ulang relasi many-to-many anotasi
            if (isset($validatedData['anotasi_ids'])) {
                $syncData = [];
                foreach ($validatedData['anotasi_ids'] as $anotasiId) {
                    $syncData[$anotasiId] = [
                        'frekuensi_dominan' => $validatedData['frekuensi_dominan'][$anotasiId] ?? null,
                    ];
                }
                $rekaman->anotasis()->sync($syncData);
            } else {
                $rekaman->anotasis()->detach();
            }

            return $rekaman;
        });
    }
}
