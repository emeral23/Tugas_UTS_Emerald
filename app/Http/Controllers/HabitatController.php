<?php

namespace App\Http\Controllers;

use App\Models\Habitat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HabitatController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $items = Habitat::query()
            ->when($request->filled('q'), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_habitat', 'like', "%{$search}%")
                        ->orWhere('nama_habitat', 'like', "%{$search}%");
                });
            })
            ->withCount('rekamans')
            ->orderBy('nama_habitat')
            ->paginate(10)
            ->withQueryString();

        return view('catalogs.index', [
            'title' => 'Habitat & Ekosistem',
            'description' => 'Kelola daftar habitat untuk mengelompokkan data rekaman.',
            'items' => $items,
            'columns' => [
                ['label' => 'Kode Habitat', 'key' => 'kode_habitat'],
                ['label' => 'Nama Habitat', 'key' => 'nama_habitat'],
                ['label' => 'Jumlah Rekaman', 'key' => 'rekamans_count'],
                ['label' => 'Deskripsi', 'key' => 'deskripsi'],
            ],
            'indexRoute' => 'habitats.index',
            'createRoute' => 'habitats.create',
            'editRoute' => 'habitats.edit',
            'destroyRoute' => 'habitats.destroy',
            'emptyMessage' => 'Belum ada data habitat atau ekosistem.',
        ]);
    }

    public function create(): View
    {
        return $this->formView(new Habitat(), false);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_habitat' => ['required', 'string', 'max:20', 'unique:habitats,kode_habitat'],
            'nama_habitat' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Habitat::create($data);

        return redirect()->route('habitats.index')->with('success', 'Habitat berhasil ditambahkan.');
    }

    public function edit(Habitat $habitat): View
    {
        return $this->formView($habitat, true);
    }

    public function update(Request $request, Habitat $habitat): RedirectResponse
    {
        $data = $request->validate([
            'kode_habitat' => ['required', 'string', 'max:20', Rule::unique('habitats', 'kode_habitat')->ignore($habitat->id)],
            'nama_habitat' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $habitat->update($data);

        return redirect()->route('habitats.index')->with('success', 'Habitat berhasil diperbarui.');
    }

    public function destroy(Habitat $habitat): RedirectResponse
    {
        if ($habitat->rekamans()->exists()) {
            return redirect()->route('habitats.index')->with('error', 'Habitat tidak dapat dihapus karena masih digunakan oleh data rekaman.');
        }

        $habitat->delete();

        return redirect()->route('habitats.index')->with('success', 'Habitat berhasil dihapus.');
    }

    private function formView(Habitat $habitat, bool $isEditing): View
    {
        return view('catalogs.form', [
            'title' => ($isEditing ? 'Edit' : 'Tambah') . ' Habitat & Ekosistem',
            'description' => 'Isi informasi habitat atau ekosistem.',
            'indexRoute' => 'habitats.index',
            'actionRoute' => $isEditing ? 'habitats.update' : 'habitats.store',
            'routeParameters' => $isEditing ? $habitat : [],
            'httpMethod' => $isEditing ? 'PUT' : 'POST',
            'submitLabel' => $isEditing ? 'Simpan Perubahan' : 'Simpan Habitat',
            'fields' => [
                ['name' => 'kode_habitat', 'label' => 'Kode Habitat', 'value' => $habitat->kode_habitat, 'required' => true, 'maxLength' => 20, 'placeholder' => 'Contoh: HBT-001'],
                ['name' => 'nama_habitat', 'label' => 'Nama Habitat', 'value' => $habitat->nama_habitat, 'required' => true, 'maxLength' => 100, 'placeholder' => 'Contoh: Hutan Mangrove'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'value' => $habitat->deskripsi, 'type' => 'textarea', 'rows' => 4],
            ],
        ]);
    }
}
