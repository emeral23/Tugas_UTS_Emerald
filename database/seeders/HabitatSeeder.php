<?php

namespace Database\Seeders;

use App\Models\Habitat;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitatSeeder extends Seeder
{
    public function run(): void
    {
        Habitat::create([
            'kode_habitat' => 'HAB-01',
            'nama_habitat' => 'Hutan Hujan Tropis Dataran Rendah',
            'deskripsi' => 'Ekosistem hutan dengan keanekaragaman hayati suara serangga dan burung tinggi.',
        ]);

        Habitat::create([
            'kode_habitat' => 'HAB-02',
            'nama_habitat' => 'Ekosistem Mangrove Pesisir',
            'deskripsi' => 'Kawasan pasang surut dengan dominasi fauna amfibi dan burung pantai.',
        ]);
    }
}
