<?php

namespace Database\Seeders;

use App\Models\Anotasi;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnotasiSeeder extends Seeder
{
    public function run(): void
    {
        Anotasi::create([
            'kode_anotasi' => 'ANT-01',
            'jenis_suara' => 'Panggilan Kawin (Mating Call)',
            'keterangan' => 'Frekuensi harmonik berulang pada jam aktif malam hari.',
        ]);

        Anotasi::create([
            'kode_anotasi' => 'ANT-02',
            'jenis_suara' => 'Ekolokasi Kelelawar',
            'keterangan' => 'Gelombang ultrasonik terdeteksi saat pencarian mangsa.',
        ]);

        Anotasi::create([
            'kode_anotasi' => 'ANT-03',
            'jenis_suara' => 'Teritorial / Peringatan',
            'keterangan' => 'Suara amplitudo tinggi untuk mempertahankan wilayah.',
        ]);
    }
}
