<?php

namespace App\Http\Controllers;

use App\Models\Anotasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnotasiController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $items = Anotasi::query()
            ->when($request->filled('q'), function ($query) use ($search) {
                $query->where('kode_anotasi', 'like', "%{$search}%")
                    ->orWhere('jenis_suara', 'like', "%{$search}%");
            })
            ->withCount('rekamans')
            ->orderBy('jenis_suara')
            ->paginate(10)
            ->withQueryString();

        return view('catalogs.index', [
            'title' => 'Anotasi Suara',
            'description' => 'Kelola jenis suara yang dapat ditautkan pada data rekaman.',
            'items' => $items,
            'columns' => [
                ['label' => 'Kode Anotasi', 'key' => 'kode_anotasi'],
                ['label' => 'Jenis Suara', 'key' => 'jenis_suara'],
                ['label' => 'Jumlah Rekaman', 'key' => 'rekamans_count'],
                ['label' => 'Keterangan', 'key' => 'keterangan'],
            ],
            'indexRoute' => 'anotasi.index',
            'createRoute' => 'anotasi.create',
            'editRoute' => 'anotasi.edit',
            'destroyRoute' => 'anotasi.destroy',
            'emptyMessage' => 'Belum ada jenis anotasi suara.',
        ]);
    }

    public function create(): View
    {
        return $this->formView(new Anotasi(), false);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_anotasi' => ['required', 'string', 'max:20', 'unique:anotasis,kode_anotasi'],
            'jenis_suara' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Anotasi::create($data);

        return redirect()->route('anotasi.index')->with('success', 'Anotasi suara berhasil ditambahkan.');
    }

    public function edit(Anotasi $anotasi): View
    {
        return $this->formView($anotasi, true);
    }

    public function update(Request $request, Anotasi $anotasi): RedirectResponse
    {
        $data = $request->validate([
            'kode_anotasi' => ['required', 'string', 'max:20', Rule::unique('anotasis', 'kode_anotasi')->ignore($anotasi->id)],
            'jenis_suara' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $anotasi->update($data);

        return redirect()->route('anotasi.index')->with('success', 'Anotasi suara berhasil diperbarui.');
    }

    public function destroy(Anotasi $anotasi): RedirectResponse
    {
        if ($anotasi->rekamans()->exists()) {
            return redirect()->route('anotasi.index')->with('error', 'Anotasi tidak dapat dihapus karena masih digunakan oleh data rekaman.');
        }

        $anotasi->delete();

        return redirect()->route('anotasi.index')->with('success', 'Anotasi suara berhasil dihapus.');
    }

    private function formView(Anotasi $anotasi, bool $isEditing): View
    {
        return view('catalogs.form', [
            'title' => ($isEditing ? 'Edit' : 'Tambah') . ' Anotasi Suara',
            'description' => 'Isi informasi jenis suara untuk anotasi rekaman.',
            'indexRoute' => 'anotasi.index',
            'actionRoute' => $isEditing ? 'anotasi.update' : 'anotasi.store',
            'routeParameters' => $isEditing ? $anotasi : [],
            'httpMethod' => $isEditing ? 'PUT' : 'POST',
            'submitLabel' => $isEditing ? 'Simpan Perubahan' : 'Simpan Anotasi',
            'fields' => [
                ['name' => 'kode_anotasi', 'label' => 'Kode Anotasi', 'value' => $anotasi->kode_anotasi, 'required' => true, 'maxLength' => 20, 'placeholder' => 'Contoh: ANT-001'],
                ['name' => 'jenis_suara', 'label' => 'Jenis Suara', 'value' => $anotasi->jenis_suara, 'required' => true, 'maxLength' => 100, 'placeholder' => 'Contoh: Panggilan kawin'],
                ['name' => 'keterangan', 'label' => 'Keterangan', 'value' => $anotasi->keterangan, 'type' => 'textarea', 'rows' => 4],
            ],
        ]);
    }
}
