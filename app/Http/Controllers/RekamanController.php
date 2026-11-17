<?php

namespace App\Http\Controllers;

use App\Models\Rekaman;
use App\Models\Habitat;
use App\Models\Anotasi;
use App\Services\RekamanService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RekamanController extends Controller
{
    // Menggunakan Dependency Injection untuk Service (materi pertemuan 2 & 4)
    protected RekamanService $rekamanService;

    public function __construct(RekamanService $rekamanService)
    {
        $this->rekamanService = $rekamanService;
    }

    /**
     * Menampilkan daftar data rekaman lengkap dengan pencarian dan filter (materi pertemuan 5).
     */
    public function index(Request $request): View
    {
            $rekamans = Rekaman::query()
                ->with(['habitat', 'titikPerekaman', 'anotasis'])
                ->when($request->filled('q'), function ($query) use ($request) {
                    $keyword = $request->input('q');
                    $query->where('kode_rekaman', 'like', "%{$keyword}%")
                        ->orWhere('nama_rekaman', 'like', "%{$keyword}%")
                        ->orWhere('perekam', 'like', "%{$keyword}%");
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();

            return view('rekaman.index', compact('rekamans'));
        }

    /**
     * Menampilkan form tambah data rekaman.
     */
    public function create(): View
    {
        $habitats = Habitat::orderBy('nama_habitat')->get();
        $anotasis = Anotasi::orderBy('jenis_suara')->get();

        return view('rekaman.create', compact('habitats', 'anotasis'));
    }

    /**
     * Menyimpan data baru menggunakan RekamanService & Database Transaction (materi pertemuan 4).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'habitat_id' => ['required', 'exists:habitats,id'],
            'kode_rekaman' => ['required', 'string', 'max:30', 'unique:rekamans,kode_rekaman'],
            'nama_rekaman' => ['required', 'string', 'max:150'],
            'tanggal_perekaman' => ['required', 'date'],
            'perekam' => ['required', 'string', 'max:100'],
            'status_validasi' => ['sometimes', 'boolean'],
            // Validasi detail titik perekaman (1-to-1)
            'koordinat_gps' => ['required', 'string', 'max:50'],
            'lokasi_spesifik' => ['required', 'string', 'max:150'],
            'elevasi_mdpl' => ['nullable', 'numeric'],
            // Validasi pilihan jamak anotasi (Many-to-Many)
            'anotasi_ids' => ['nullable', 'array'],
            'anotasi_ids.*' => ['exists:anotasis,id'],
            'frekuensi_dominan' => ['nullable', 'array'],
        ]);

        $this->rekamanService->storeData($validated);

        return redirect()->route('rekaman.index')
                         ->with('success', 'Metadata rekaman bioakustik berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail lengkap suatu rekaman.
     */
    public function show(Rekaman $rekaman): View
    {
        $rekaman->load(['habitat', 'titikPerekaman', 'anotasis']);

        return view('rekaman.show', compact('rekaman'));
    }

    /**
     * Menampilkan form edit data rekaman.
     */
    public function edit(Rekaman $rekaman): View
    {
        $rekaman->load(['habitat', 'titikPerekaman', 'anotasis']);
        $habitats = Habitat::orderBy('nama_habitat')->get();
        $anotasis = Anotasi::orderBy('jenis_suara')->get();

        $selectedAnotasis = $rekaman->anotasis->pluck('id')->all();

        return view('rekaman.edit', compact('rekaman', 'habitats', 'anotasis', 'selectedAnotasis'));
    }

    /**
     * Memperbarui data rekaman melalui service.
     */
    public function update(Request $request, Rekaman $rekaman): RedirectResponse
    {
        $validated = $request->validate([
            'habitat_id' => ['required', 'exists:habitats,id'],
            'kode_rekaman' => ['required', 'string', 'max:30', 'unique:rekamans,kode_rekaman,' . $rekaman->id],
            'nama_rekaman' => ['required', 'string', 'max:150'],
            'tanggal_perekaman' => ['required', 'date'],
            'perekam' => ['required', 'string', 'max:100'],
            'status_validasi' => ['sometimes', 'boolean'],
            'koordinat_gps' => ['required', 'string', 'max:50'],
            'lokasi_spesifik' => ['required', 'string', 'max:150'],
            'elevasi_mdpl' => ['nullable', 'numeric'],
            'anotasi_ids' => ['nullable', 'array'],
            'anotasi_ids.*' => ['exists:anotasis,id'],
            'frekuensi_dominan' => ['nullable', 'array'],
        ]);

        $this->rekamanService->updateData($rekaman, $validated);

        return redirect()->route('rekaman.index')
                         ->with('success', 'Metadata rekaman bioakustik berhasil diperbarui.');
    }

    /**
     * Menghapus data rekaman.
     */
    public function destroy(Rekaman $rekaman): RedirectResponse
    {
        // Berkat cascadeOnDelete di migration, titik perekaman otomatis terhapus
        $rekaman->delete();

        return redirect()->route('rekaman.index')
                         ->with('success', 'Metadata rekaman berhasil dihapus.');
    }
}
