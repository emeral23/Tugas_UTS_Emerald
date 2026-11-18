# Class Diagram Sistem Perekaman Suara

Diagram ini merangkum class aplikasi pada layer model, service, dan controller berdasarkan source code proyek.

```mermaid
classDiagram
    direction TB

    class Controller {
    }

    class DashboardController {
        +index() View
    }

    class RekamanController {
        -rekamanService RekamanService
        +__construct(rekamanService)
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +show(rekaman) View
        +edit(rekaman) View
        +update(request, rekaman) RedirectResponse
        +destroy(rekaman) RedirectResponse
    }

    class HabitatController {
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +edit(habitat) View
        +update(request, habitat) RedirectResponse
        +destroy(habitat) RedirectResponse
        -formView(habitat, isEditing) View
    }

    class TitikPerekamanController {
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +edit(titikPerekaman) View
        +update(request, titikPerekaman) RedirectResponse
        +destroy(titikPerekaman) RedirectResponse
        -formView(titikPerekaman, isEditing) View
    }

    class AnotasiController {
        +index(request) View
        +create() View
        +store(request) RedirectResponse
        +edit(anotasi) View
        +update(request, anotasi) RedirectResponse
        +destroy(anotasi) RedirectResponse
        -formView(anotasi, isEditing) View
    }

    class RekamanService {
        +storeData(validatedData) Rekaman
        +updateData(rekaman, validatedData) Rekaman
    }

    class Habitat {
        +id
        +kode_habitat
        +nama_habitat
        +deskripsi
        +rekamans() HasMany
    }

    class TitikPerekaman {
        +id
        +koordinat_gps
        +lokasi_spesifik
        +elevasi_mdpl
        +rekaman() HasOne
    }

    class Rekaman {
        +id
        +habitat_id
        +titik_perekaman_id
        +kode_rekaman
        +nama_rekaman
        +tanggal_perekaman
        +perekam
        +status_validasi
        +habitat() BelongsTo
        +titikPerekaman() BelongsTo
        +anotasis() BelongsToMany
    }

    class Anotasi {
        +id
        +kode_anotasi
        +jenis_suara
        +keterangan
        +rekamans() BelongsToMany
    }

    class User {
        +id
        +name
        +email
        +password
        +casts() array
    }

    Controller <|-- DashboardController
    Controller <|-- RekamanController
    Controller <|-- HabitatController
    Controller <|-- TitikPerekamanController
    Controller <|-- AnotasiController

    Habitat "1" --> "0..*" Rekaman : rekamans
    TitikPerekaman "1" --> "0..1" Rekaman : rekaman
    Rekaman "0..*" --> "0..*" Anotasi : anotasis

    RekamanController --> RekamanService : menggunakan
    RekamanController ..> Rekaman : mengelola
    RekamanController ..> Habitat : memilih
    RekamanController ..> Anotasi : memilih
    DashboardController ..> Rekaman : ringkasan
    DashboardController ..> Habitat : statistik
    DashboardController ..> TitikPerekaman : statistik
    DashboardController ..> Anotasi : statistik
    HabitatController ..> Habitat : mengelola
    TitikPerekamanController ..> TitikPerekaman : mengelola
    AnotasiController ..> Anotasi : mengelola
    RekamanService ..> Rekaman : menyimpan dan memperbarui
    RekamanService ..> TitikPerekaman : menyimpan dan memperbarui
```

## Keterangan

- Relasi **Rekaman–Anotasi** direalisasikan melalui tabel pivot `anotasi_rekaman`, yang juga menyimpan `frekuensi_dominan`.
- `RekamanService` menangani penyimpanan dan pembaruan rekaman, detail titik perekaman, dan anotasi dalam transaksi database.
- `User` adalah model autentikasi Laravel dan belum memiliki relasi dengan model domain pada diagram ini.
- `Controller` merupakan base controller aplikasi; tipe seperti `Request`, `View`, dan `RedirectResponse` berasal dari Laravel dan tidak ditampilkan sebagai class aplikasi.
