# ERD — Sistem Perekaman Suara

Diagram Entity Relationship (ERD) di bawah ini menggambarkan struktur data utama aplikasi, yang disusun berdasarkan file migration dan model pada aplikasi Laravel.

```mermaid
erDiagram
    HABITATS ||--o{ REKAMANS : memiliki
    TITIK_PEREKAMANS ||--o| REKAMANS : lokasi
    REKAMANS ||--o{ ANOTASI_REKAMAN : memiliki
    ANOTASIS ||--o{ ANOTASI_REKAMAN : digunakan

    HABITATS {
        bigint id PK
        varchar kode_habitat UK
        varchar nama_habitat
        text deskripsi "nullable"
        timestamp created_at
        timestamp updated_at
    }

    TITIK_PEREKAMANS {
        bigint id PK
        varchar koordinat_gps
        varchar lokasi_spesifik
        decimal elevasi_mdpl "nullable"
        timestamp created_at
        timestamp updated_at
    }

    REKAMANS {
        bigint id PK
        bigint habitat_id FK
        bigint titik_perekaman_id FK_UK
        varchar kode_rekaman UK
        varchar nama_rekaman
        date tanggal_perekaman
        varchar perekam
        boolean status_validasi "default false"
        timestamp created_at
        timestamp updated_at
    }

    ANOTASIS {
        bigint id PK
        varchar kode_anotasi UK
        varchar jenis_suara
        text keterangan "nullable"
        timestamp created_at
        timestamp updated_at
    }

    ANOTASI_REKAMAN {
        bigint id PK
        bigint rekaman_id FK
        bigint anotasi_id FK
        varchar frekuensi_dominan "nullable"
        timestamp created_at
        timestamp updated_at
    }
```

## Relasi Antar Entitas

| Relasi | Kardinalitas | Keterangan |
| --- | --- | --- |
| Habitat → Rekaman | 1 : N | Satu habitat dapat memiliki banyak rekaman. Setiap rekaman wajib terhubung ke tepat satu habitat. |
| Titik Perekaman → Rekaman | 1 : 0..1 | Satu titik perekaman paling banyak digunakan oleh satu rekaman. Kolom `rekamans.titik_perekaman_id` bersifat wajib (required) dan unik (unique), sehingga setiap rekaman memiliki tepat satu titik perekaman. |
| Rekaman ↔ Anotasi | N : M | Relasi banyak-ke-banyak melalui tabel penghubung `anotasi_rekaman`. Kombinasi `rekaman_id` dan `anotasi_id` harus unik, sementara `frekuensi_dominan` merupakan atribut tambahan pada relasi tersebut. |

## Aturan Penghapusan Data (Integritas Referensial)

- **Habitat**: tidak dapat dihapus selama masih digunakan oleh rekaman (*restrict*).
- **Titik perekaman**: ketika dihapus, rekaman yang terkait akan ikut terhapus (*cascade*).
- **Rekaman**: ketika dihapus, baris penghubung di tabel `anotasi_rekaman` akan ikut terhapus (*cascade*).
- **Anotasi**: tidak dapat dihapus selama masih digunakan oleh rekaman (*restrict*).

## Keterangan Notasi

- `PK` — *Primary Key*: identifier unik setiap baris.
- `FK` — *Foreign Key*: kolom yang merujuk ke primary key tabel lain.
- `UK` — *Unique Key*: kolom dengan nilai yang harus unik.
- `FK_UK` — *Foreign Key* yang sekaligus bersifat unik.
- `nullable` — kolom tersebut boleh dikosongkan (NULL).

## Catatan

- Tabel `users` tersedia untuk keperluan autentikasi bawaan Laravel, namun tidak memiliki relasi foreign key ke entitas domain di atas.
- Tabel pendukung Laravel (cache, queue, session, dan password reset) tidak ditampilkan karena bukan bagian dari domain aplikasi perekaman suara.
