<?php

namespace App\Http\Controllers;

use App\Models\TitikPerekaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TitikPerekamanController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $items = TitikPerekaman::query()
            ->when($request->filled('q'), function ($query) use ($search) {
                $query->where('lokasi_spesifik', 'like', "%{$search}%")
                    ->orWhere('koordinat_gps', 'like', "%{$search}%");
            })
            ->with('rekaman')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('catalogs.index', [
            'title' => 'Titik Perekaman',
            'description' => 'Kelola koordinat dan lokasi pengambilan rekaman suara.',
            'items' => $items,
            'columns' => [
                ['label' => 'Lokasi Spesifik', 'key' => 'lokasi_spesifik'],
                ['label' => 'Koordinat GPS', 'key' => 'koordinat_gps'],
                ['label' => 'Elevasi (MDPL)', 'key' => 'elevasi_mdpl'],
                ['label' => 'Kode Rekaman', 'key' => 'rekaman.kode_rekaman'],
            ],
            'indexRoute' => 'titik-perekaman.index',
            'createRoute' => 'titik-perekaman.create',
            'editRoute' => 'titik-perekaman.edit',
            'destroyRoute' => 'titik-perekaman.destroy',
            'emptyMessage' => 'Belum ada titik perekaman.',
        ]);
    }

    public function create(): View
    {
        return $this->formView(new TitikPerekaman(), false);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'koordinat_gps' => ['required', 'string', 'max:50'],
            'lokasi_spesifik' => ['required', 'string', 'max:150'],
            'elevasi_mdpl' => ['nullable', 'numeric'],
        ]);

        TitikPerekaman::create($data);

        return redirect()->route('titik-perekaman.index')->with('success', 'Titik perekaman berhasil ditambahkan.');
    }

    public function edit(TitikPerekaman $titikPerekaman): View
    {
        return $this->formView($titikPerekaman, true);
    }

    public function update(Request $request, TitikPerekaman $titikPerekaman): RedirectResponse
    {
        $data = $request->validate([
            'koordinat_gps' => ['required', 'string', 'max:50'],
            'lokasi_spesifik' => ['required', 'string', 'max:150'],
            'elevasi_mdpl' => ['nullable', 'numeric'],
        ]);

        $titikPerekaman->update($data);

        return redirect()->route('titik-perekaman.index')->with('success', 'Titik perekaman berhasil diperbarui.');
    }

    public function destroy(TitikPerekaman $titikPerekaman): RedirectResponse
    {
        if ($titikPerekaman->rekaman()->exists()) {
            return redirect()->route('titik-perekaman.index')->with('error', 'Titik tidak dapat dihapus karena masih digunakan oleh data rekaman.');
        }

        $titikPerekaman->delete();

        return redirect()->route('titik-perekaman.index')->with('success', 'Titik perekaman berhasil dihapus.');
    }

    private function formView(TitikPerekaman $titikPerekaman, bool $isEditing): View
    {
        return view('catalogs.form', [
            'title' => ($isEditing ? 'Edit' : 'Tambah') . ' Titik Perekaman',
            'description' => 'Isi informasi lokasi pengambilan rekaman.',
            'indexRoute' => 'titik-perekaman.index',
            'actionRoute' => $isEditing ? 'titik-perekaman.update' : 'titik-perekaman.store',
            'routeParameters' => $isEditing ? $titikPerekaman : [],
            'httpMethod' => $isEditing ? 'PUT' : 'POST',
            'submitLabel' => $isEditing ? 'Simpan Perubahan' : 'Simpan Titik',
            'fields' => [
                ['name' => 'koordinat_gps', 'label' => 'Koordinat GPS', 'value' => $titikPerekaman->koordinat_gps, 'required' => true, 'maxLength' => 50, 'placeholder' => 'Contoh: -6.2146, 106.8451'],
                ['name' => 'lokasi_spesifik', 'label' => 'Lokasi Spesifik', 'value' => $titikPerekaman->lokasi_spesifik, 'required' => true, 'maxLength' => 150, 'placeholder' => 'Contoh: Sisi utara kawasan'],
                ['name' => 'elevasi_mdpl', 'label' => 'Elevasi (MDPL)', 'value' => $titikPerekaman->elevasi_mdpl, 'type' => 'number', 'step' => '0.01', 'placeholder' => 'Opsional'],
            ],
        ]);
    }
}
