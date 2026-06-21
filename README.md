# SQA Mahasiswa Baru USTI — Panduan Setup & Pengujian

Project UAS Software Quality Assurance (SQA) — Teknik Informatika, USTI, TA. 2026
**Penguji:** Lingga Prestiawan · NIM 2310031802009

Paket ini berisi seluruh konfigurasi untuk pengujian **White Box**, **Black Box**,
pencatatan test case di **Jira**, dan otomasi **CI/CD** via **GitHub Actions**,
disesuaikan dengan source code `mahasiswa_baru_usti_lingga.php`.

---

## 📦 Isi Paket

| File | Fungsi |
|---|---|
| `mahasiswa_baru_usti_lingga.php` | Aplikasi yang diuji (Aplikasi Mahasiswa Baru USTI) |
| `SQA_MahasiswaBaru_USTI.side` | Selenium IDE — 4 test case + 3 suite siap run |
| `sqa-ci.yml` | GitHub Actions — 5 jobs otomatis (White Box + Black Box + Report) |
| `tests/blackbox_test.php` | Script CLI Black Box — dijalankan `php tests/blackbox_test.php` |
| `testcases-import.csv` | Jira — 8 test case siap di-import langsung |
| `sonar-project.properties` | SonarQube — konfigurasi scan kode PHP |
| `composer.json` | Dependensi PHP + shortcut `composer run test-all` |
| `phpcs.xml` | PHP CodeSniffer — aturan PSR-12 |
| `phpstan.neon` | PHPStan — konfigurasi static analysis level 5 |
| `README.md` | Panduan ini |

---

## ▶ Urutan Penggunaan

### 1. Ekstrak & Tempatkan File
Taruh seluruh isi folder ini di `htdocs/sqa/` (XAMPP) atau `www/sqa/` (Laragon).

```
htdocs/sqa/
├── mahasiswa_baru_usti_lingga.php
├── tests/blackbox_test.php
├── composer.json
├── phpcs.xml
├── phpstan.neon
├── sonar-project.properties
├── SQA_MahasiswaBaru_USTI.side
├── testcases-import.csv
└── .github/workflows/sqa-ci.yml
```

### 2. Install Dependency PHP
```bash
cd htdocs/sqa
composer install
```
Ini akan menginstal **PHPCS** (PSR-12 checker) dan **PHPStan** (static analysis) — keduanya dipakai untuk **White Box Testing**.

### 3. White Box Testing — PHPCS & PHPStan
```bash
composer run lint              # cek gaya kode PSR-12
composer run static-analysis   # cek potensi bug level 5
```
📸 **Screenshot:** hasil output terminal untuk kedua perintah di atas, lampirkan di laporan bagian "White Box Testing".

### 4. Black Box Testing — Selenium IDE (UI)
1. Install ekstensi **Selenium IDE** di Chrome/Firefox.
2. Jalankan server PHP: `php -S localhost/sqa` lewat XAMPP/Laragon seperti biasa.
3. Buka Selenium IDE → **File → Open Project** → pilih `SQA_MahasiswaBaru_USTI.side`.
4. Jalankan salah satu suite:
   - **Suite - Skenario Positif** → TC01, TC02, TC03
   - **Suite - Skenario Negatif** → TC04
   - **Suite - Full Regresi** → semua TC sekaligus

📸 **Screenshot:** hasil run (hijau = lulus) untuk tiap test case, lampirkan di laporan bagian "Pengujian Manual SQA" sesuai tabel D.2.

### 5. Black Box Testing — CLI Script
```bash
php tests/blackbox_test.php
```
Script ini mengirim request HTTP langsung ke aplikasi (tanpa browser) dan mencetak ringkasan PASS/FAIL untuk 4 skenario di terminal.

📸 **Screenshot:** output terminal, lampirkan sebagai bukti pengujian otomatis tambahan.

### 6. SonarQube Scan
1. Jalankan SonarQube server (lokal via Docker, atau pakai SonarCloud).
2. Buat token di SonarQube/SonarCloud, lalu jalankan:
   ```bash
   sonar-scanner -Dsonar.token=ISI_TOKEN_DISINI -Dsonar.host.url=http://localhost:9000
   ```
   *(Atau edit `sonar.host.url` langsung di `sonar-project.properties`)*

📸 **Screenshot:** dashboard hasil scan (bugs, code smells, security hotspots), lampirkan di laporan bagian "Black Box dan White Box Testing".

### 7. Jira — Import Test Case
1. Buka project Jira kamu → **Project Settings → Import**, atau gunakan plugin **Xray/Zephyr** jika tersedia.
2. Pilih **CSV Import**, upload `testcases-import.csv`.
3. Map kolom CSV ke field Jira (Summary, Description, Priority, Steps, Expected Result, dst).
4. Setelah import, jalankan pengujian manual lalu update **Status** tiap TC:
   - **Done** = lulus / sesuai harapan
   - **Failed** = tidak sesuai harapan

📸 **Screenshot:** daftar 8 test case di Jira beserta status akhirnya, lampirkan di laporan.

### 8. CI/CD — GitHub Actions
1. Push seluruh project ke repository GitHub.
2. Pindahkan `sqa-ci.yml` ke path `.github/workflows/sqa-ci.yml` (sudah disiapkan strukturnya).
3. (Opsional, untuk job SonarQube) Tambahkan repo secrets:
   - `SONAR_TOKEN`
   - `SONAR_HOST_URL`
4. Buka tab **Actions** di GitHub → pipeline otomatis berjalan setiap push/PR ke branch `main`, terdiri dari 5 job:
   1. White Box - PHPCS & PHPStan
   2. White Box - SonarQube Scan
   3. Black Box - CLI Test Script
   4. Black Box - Selenium UI Suite
   5. Report - Ringkasan Hasil

📸 **Screenshot:** tab Actions dengan status ✅ hijau di tiap job, lampirkan sebagai bukti CI/CD berjalan.

---

## ✅ Checklist Screenshot untuk Laporan UAS

Sesuai format dokumen `Format_PROJECT_UAS_SQA-26`, siapkan screenshot berikut:

- [ ] **Bagian A & B** — Screenshot materi SQA & Tools SQA (sesuai pedoman dosen)
- [ ] **Bagian C** — Screenshot konsep Black Box, White Box, dan CI/CD
- [ ] **Bagian D.1** — Screenshot source code aplikasi + tabel identitas pengujian
- [ ] **Bagian D.2** — Screenshot 4 skenario pengujian manual (Selenium IDE atau manual browser):
  - [ ] TC01 - Entri Karakter (Enter) → sebelum & sesudah submit
  - [ ] TC02 - Entri Number (Enter) → sebelum & sesudah submit
  - [ ] TC03 - Entri Karakter+Number (SAVE) → sebelum & sesudah submit
  - [ ] TC04 - Tanpa entri / kosong (SAVE) → sebelum & sesudah submit
- [ ] **Bagian D.3** — Screenshot hasil:
  - [ ] PHPCS & PHPStan (White Box)
  - [ ] SonarQube dashboard (White Box)
  - [ ] Black Box CLI test output
  - [ ] Jira test case list & status
  - [ ] GitHub Actions pipeline (5 jobs hijau)

---

## ⚠️ Catatan

- File `sqa-ci.yml` perlu dipindah ke `.github/workflows/sqa-ci.yml` agar dikenali GitHub Actions.
- Job SonarQube di pipeline akan gagal (skip) jika secret `SONAR_TOKEN`/`SONAR_HOST_URL` belum diisi — itu wajar, screenshot kegagalannya pun masih bisa dijadikan bukti percobaan jika diperlukan dosen, atau cukup hapus job tersebut jika tidak dipakai.
- Konfigurasi ini disesuaikan dengan isi **aktual** dari `mahasiswa_baru_usti_lingga.php` (tanpa fitur riwayat/log), bukan template generik.
