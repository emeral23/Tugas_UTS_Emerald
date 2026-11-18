# Sistem Perekaman Suara - Dokumentasi ERD

Dokumentasi struktur database utama untuk aplikasi Sistem Perekaman Suara yang dibangun menggunakan Laravel.

## 1. Tabel \`habitats\`

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| \`id\` | bigint | Primary Key (Identifikasi unik baris) |
| \`kode_habitat\` | varchar | Unique Key (Kode unik habitat) |
| \`nama_habitat\` | varchar | Nama habitat (contoh: Hutan Lindung, Mangrove) |
| \`deskripsi\` | text | Penjelasan opsional (*nullable*) |
| \`created_at\` | timestamp | Waktu data dibuat |
| \`updated_at\` | timestamp | Waktu data diperbarui |

---

## 2. Tabel \`titik_perekamans\`

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| \`id\` | bigint | Primary Key (Identifikasi unik baris) |
| \`koordinat_gps\` | varchar | Titik koordinat GPS lokasi |
| \`lokasi_spesifik\` | varchar | Keterangan detail lokasi di lapangan |
| \`elevasi_mdpl\` | decimal | Ketinggian dari permukaan laut, opsional (*nullable*) |
| \`created_at\` | timestamp | Waktu data dibuat |
| \`updated_at\` | timestamp | Waktu data diperbarui |

---

## 3. Tabel \`rekamans\`

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| \`id\` | bigint | Primary Key (Identifikasi unik baris) |
| \`habitat_id\` | bigint | Foreign Key ke tabel \`habitats\` (Wajib diisi) |
| \`titik_perekaman_id\` | bigint | Foreign Key & Unique Key ke \`titik_perekamans\` (1 ke 1) |
| \`kode_rekaman\` | varchar | Unique Key (Kode unik rekaman) |
| \`nama_rekaman\` | varchar | Judul atau nama file rekaman |
| \`tanggal_perekaman\` | date | Tanggal saat perekaman diambil |
| \`perekam\` | varchar | Nama petugas atau alat perekam |
| \`status_validasi\` | boolean | Status validasi data, *default false* |
| \`created_at\` | timestamp | Waktu data dibuat |
| \`updated_at\` | timestamp | Waktu data diperbarui |

---

## 4. Tabel \`anotasis\`

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| \`id\` | bigint | Primary Key (Identifikasi unik baris) |
| \`kode_anotasi\` | varchar | Unique Key (Kode unik anotasi) |
| \`jenis_suara\` | varchar | Kategori jenis suara (misal: Burung, Serangga, Amfibi) |
| \`keterangan\` | text | Catatan tambahan, opsional (*nullable*) |
| \`created_at\` | timestamp | Waktu data dibuat |
| \`updated_at\` | timestamp | Waktu data diperbarui |

---

## 5. Tabel \`anotasi_rekaman\`
Tabel perantara (*pivot table*) untuk menangani relasi *many-to-many* antara tabel \`rekamans\` dan \`anotasis\`.

| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| \`id\` | bigint | Primary Key (Identifikasi unik baris) |
| \`rekaman_id\` | bigint | Foreign Key merujuk ke tabel \`rekamans\` |
| \`anotasi_id\` | bigint | Foreign Key merujuk ke tabel \`anotasis\` |
| \`frekuensi_dominan\` | varchar | Atribut tambahan frekuensi suara, opsional (*nullable*) |
| \`created_at\` | timestamp | Waktu data dibuat |
| \`updated_at\` | timestamp | Waktu data diperbarui |

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
