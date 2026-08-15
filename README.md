# Sistem Klasifikasi Kelayakan Kualitas Telur (Algoritma C4.5)
**Peternakan Ayam Petelur Rajadesa Berdasarkan Karakteristik Fisik**

Aplikasi sistem informasi & pendukung keputusan berbasis web menggunakan **Laravel (PHP 8.2+)** dan **MySQL** yang mengimplementasikan **Algoritma C4.5 (Decision Tree)** untuk mengklasifikasikan kualitas telur (*Layak Jual* vs *Tidak Layak Jual*) berdasarkan parameter fisik:
1. **Berat Telur (Gram)**
2. **Diameter Telur (Cm)**
3. **Kondisi Cangkang** (*Normal* / *Retak*)
4. **Warna Cangkang** (*Cokelat Tua* / *Cokelat Muda*)

---

## 🛠️ Fitur Utama Sistem

1. **Multi-Role Authentication**:
   - **Administrasi (Admin)**: Akses Dashboard, Manajemen Dataset Latih, Perhitungan C4.5 (*Entropy*, *Gain*, *Tree*, *Confusion Matrix* 100% Presisi), Rekapitulasi & Cetak Laporan PDF, serta Kelola User.
   - **Pekerja Kandang**: Input Pemanenan Telur (Prediksi C4.5 Instan) & Riwayat Panen.
2. **Transparansi Perhitungan Algoritma C4.5**:
   - Menampilkan detail step-by-step perhitungan matematik C4.5 sesuai spreadsheet `RUMUS C4.5.xlsx`.
   - Menentukan Root Node (`Berat Telur` &le; 53.0 Gram, Gain = 0.881291).
   - Evaluasi Confusion Matrix dengan Akurasi **100%**.
3. **Desain Light Mode Ceria**:
   - Antarmuka terang dengan warna *warm egg-yolk* & ikon telur animasi.

---

## 🚀 Panduan Instalasi & Jalankan Aplikasi (Untuk Client / XAMPP)

Berikut langkah mudah bagi client/pengguna untuk mengunduh (*clone*) dan menjalankan aplikasi di komputer/laptop lokal:

### 1. **Clone Repository dari GitHub**
Buka terminal / Command Prompt / Git Bash, lalu jalankan:
```bash
git clone https://github.com/haniriani211-web/sistemklasifikassitelur.git
cd sistemklasifikassitelur
```

### 2. **Install Dependensi PHP (Composer)**
Jalankan perintah berikut agar Composer mengabaikan perbedaan versi PHP pada XAMPP:
```bash
composer install --ignore-platform-reqs
```

### 3. **Buat File Konfigurasi Environment (`.env`)**
Salin file `.env.example` menjadi `.env`:
- **Windows (Command Prompt / PowerShell)**:
  ```cmd
  copy .env.example .env
  ```
- **Git Bash / Linux / Mac**:
  ```bash
  cp .env.example .env
  ```

### 4. **Generate Application Key**
```bash
php artisan key:generate
```

### 5. **Konfigurasi Database & Migration**
1. Pastikan Service MySQL (XAMPP / Laragon / MySQL Server) sudah aktif.
2. Buat database baru di MySQL dengan nama `sistem_c45` (melalui phpMyAdmin / MySQL CLI).
3. Sesuaikan file `.env` jika username/password database berbeda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistem_c45
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Jalankan perintah migration dan seeder dataset awal (20 sampel dari `RUMUS C4.5.xlsx`):
   ```bash
   php artisan migrate:fresh --seed
   ```

### 6. **Jalankan Web Server Lokal**
```bash
php artisan serve
```
Akses aplikasi melalui browser di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Bawaan (Default Credentials)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Administrasi (Admin)** | `admin@rajadesa.com` | `password` |
| **Pekerja Kandang** | `pekerja@rajadesa.com` | `password` |

---

## 📂 Berkas Riset & Spreadsheet
Data latih & formula perhitungan C4.5 dapat diperiksa pada file `RUMUS C4.5.xlsx` di direktori utama proyek.
