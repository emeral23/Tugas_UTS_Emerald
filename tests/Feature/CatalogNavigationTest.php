<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Anotasi;
use App\Models\Habitat;
use App\Models\Rekaman;
use App\Models\TitikPerekaman;
use Tests\TestCase;

class CatalogNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_catalog_pages_render(): void
    {
        foreach (['/dashboard', '/rekaman', '/habitats', '/titik-perekaman', '/anotasi'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_catalog_create_forms_render(): void
    {
        foreach (['/habitats/create', '/titik-perekaman/create', '/anotasi/create'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_reference_records_can_be_created_and_updated(): void
    {
        $this->post('/habitats', [
            'kode_habitat' => 'HBT-001',
            'nama_habitat' => 'Hutan Mangrove',
            'deskripsi' => 'Habitat pesisir.',
        ])->assertRedirect('/habitats');

        $habitat = Habitat::firstOrFail();
        $this->get("/habitats/{$habitat->id}/edit")->assertOk();
        $this->put("/habitats/{$habitat->id}", [
            'kode_habitat' => 'HBT-001',
            'nama_habitat' => 'Mangrove',
            'deskripsi' => 'Habitat pesisir.',
        ])->assertRedirect('/habitats');
        $this->assertDatabaseHas('habitats', ['id' => $habitat->id, 'nama_habitat' => 'Mangrove']);
        $this->delete("/habitats/{$habitat->id}")->assertRedirect('/habitats');
        $this->assertDatabaseMissing('habitats', ['id' => $habitat->id]);

        $this->post('/titik-perekaman', [
            'koordinat_gps' => '-6.2146, 106.8451',
            'lokasi_spesifik' => 'Sisi utara kawasan',
            'elevasi_mdpl' => '12.5',
        ])->assertRedirect('/titik-perekaman');

        $titikPerekaman = TitikPerekaman::firstOrFail();
        $this->get("/titik-perekaman/{$titikPerekaman->id}/edit")->assertOk();
        $this->put("/titik-perekaman/{$titikPerekaman->id}", [
            'koordinat_gps' => '-6.2146, 106.8451',
            'lokasi_spesifik' => 'Sisi selatan kawasan',
            'elevasi_mdpl' => '13',
        ])->assertRedirect('/titik-perekaman');
        $this->assertDatabaseHas('titik_perekamans', ['id' => $titikPerekaman->id, 'lokasi_spesifik' => 'Sisi selatan kawasan']);
        $this->delete("/titik-perekaman/{$titikPerekaman->id}")->assertRedirect('/titik-perekaman');
        $this->assertDatabaseMissing('titik_perekamans', ['id' => $titikPerekaman->id]);

        $this->post('/anotasi', [
            'kode_anotasi' => 'ANT-001',
            'jenis_suara' => 'Panggilan kawin',
            'keterangan' => 'Panggilan musiman.',
        ])->assertRedirect('/anotasi');

        $anotasi = Anotasi::firstOrFail();
        $this->get("/anotasi/{$anotasi->id}/edit")->assertOk();
        $this->put("/anotasi/{$anotasi->id}", [
            'kode_anotasi' => 'ANT-001',
            'jenis_suara' => 'Panggilan teritorial',
            'keterangan' => 'Panggilan musiman.',
        ])->assertRedirect('/anotasi');
        $this->assertDatabaseHas('anotasis', ['id' => $anotasi->id, 'jenis_suara' => 'Panggilan teritorial']);
        $this->delete("/anotasi/{$anotasi->id}")->assertRedirect('/anotasi');
        $this->assertDatabaseMissing('anotasis', ['id' => $anotasi->id]);
    }

    public function test_referenced_records_cannot_be_deleted(): void
    {
        $habitat = Habitat::create([
            'kode_habitat' => 'HBT-002',
            'nama_habitat' => 'Hutan',
        ]);
        $titikPerekaman = TitikPerekaman::create([
            'koordinat_gps' => '-6.2, 106.8',
            'lokasi_spesifik' => 'Lokasi uji',
        ]);
        $rekaman = Rekaman::create([
            'habitat_id' => $habitat->id,
            'titik_perekaman_id' => $titikPerekaman->id,
            'kode_rekaman' => 'REK-001',
            'nama_rekaman' => 'Rekaman uji',
            'tanggal_perekaman' => '2026-11-18',
            'perekam' => 'Penguji',
        ]);
        $anotasi = Anotasi::create([
            'kode_anotasi' => 'ANT-002',
            'jenis_suara' => 'Suara uji',
        ]);
        $rekaman->anotasis()->attach($anotasi->id);

        $this->delete("/habitats/{$habitat->id}")
            ->assertRedirect('/habitats')
            ->assertSessionHas('error');
        $this->delete("/titik-perekaman/{$titikPerekaman->id}")
            ->assertRedirect('/titik-perekaman')
            ->assertSessionHas('error');
        $this->delete("/anotasi/{$anotasi->id}")
            ->assertRedirect('/anotasi')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('habitats', ['id' => $habitat->id]);
        $this->assertDatabaseHas('titik_perekamans', ['id' => $titikPerekaman->id]);
        $this->assertDatabaseHas('anotasis', ['id' => $anotasi->id]);
    }
}
