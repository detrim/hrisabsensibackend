# 📊 Aplikasi Manajemen Pegawai (Laravel 12)

Aplikasi ini adalah sistem manajemen data pegawai berbasis web yang dibangun menggunakan **Laravel 12**.  
Dilengkapi dengan fitur export PDF, Excel, dan manajemen user.

---

## 🚀 Fitur Utama

- [x] Manajemen data pegawai
- [x] Login Multi Role (RBAC)
- [x] Kelola User (Akun)
- [x] Data Pegawai (CRUD)
- [x] Relasi pegawai dengan user
- [x] Export data ke PDF (DOMPDF)
- [x] Export data ke Excel
- [x] Pencarian data pegawai
- [x] Bulk action (hapus & update status)
- [x] Upload & hapus foto pegawai
- [x] Validasi form
- [x] Proteksi role
- [x] Alamat dynamic (Provinsi, Kabupaten, Kecamatan) dengan AJAX autocomplete

## 🛠️ Teknologi yang Digunakan

- [x] Laravel 12
- [x] PHP
- [x] MySQL / MariaDB
- [x] Bootstrap 5
- [x] jQuery & AJAX
- [x] DOMPDF (laravel-dompdf)
- [x] Laravel Excel (maatwebsite/excel)

## 📍 Manajemen Wilayah
> Aplikasi menggunakan data wilayah Indonesia yang disimpan secara lokal di storage dalam format JSON.
Data wilayah mencakup:
- Provinsi
- Kabupaten / Kota
- Kecamatan
> Struktur data disimpan dalam bentuk hierarki ID wilayah, sehingga memudahkan proses input pegawai secara dinamis dan terstruktur.

## 🗺️ Fitur Alamat (AJAX Dynamic Dropdown)
Sistem menggunakan **AJAX autocomplete** untuk pengisian alamat secara otomatis.
### 🔎 Kecamatan (Autocomplete)
- Dropdown search dengan AJAX
- Minimal **3 karakter** untuk memunculkan data
- Data berdasarkan parameter `kecamatan`

### 🏙️ Kabupaten (Auto Fill)
- Otomatis terisi setelah kecamatan dipilih
- Field **disabled (readonly)**
- Tidak bisa diubah manual

### 🏞️ Provinsi (Auto Fill)
- Otomatis terisi setelah kecamatan dipilih
- Field **disabled (readonly)**

### ⚙️ Flow Sistem
- User ketik kecamatan (min 3 karakter)
-        ↓
- AJAX request ke server
-        ↓
- Pilih kecamatan
-        ↓
- Auto isi Kabupaten
-        ↓
- Auto isi Provinsi


# 📦 Instalasi

## 1. Clone Repository
bash
- [x] git clone https://github.com/jmcdevelopment/denitri.git
- [x] cd denitri
- [x] composer install
- [x] npm install
- [x] npm run build
- [x] cp .env.example .env
- [x] php artisan key:generate
- [x] php artisan migrate
- [x] php artisan db:seed
- [x] php artisan storage:link
- [x] php artisan app:sync-wilayah
- [x] php artisan serve

## Database Configuration

- DB_CONNECTION=mariadb  
- DB_HOST=127.0.0.1  
- DB_PORT=3306  
- DB_DATABASE=nama_database_anda  
- DB_USERNAME=root  
- DB_PASSWORD=password_anda
- SESSION_DRIVER=file
- FILESYSTEM_DISK=public
 ---
# Screenshot Halaman
## 🔐 Login Aplikasi

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/login/login.png" width="600"/>
</p>

---

## 🧑‍💼 Role: Manager HRD

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/manajerhrd/1.png" width="600"/>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/manajerhrd/2.png" width="600"/>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/manajerhrd/3.png" width="600"/>
</p>

---

## 🛠 Role: Super Admin

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/superadmin/2.png" width="600"/>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/superadmin/1.png" width="600"/>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/superadmin/3.png" width="600"/>
</p>

---

## 👨‍💻 Role: Staf Admin

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/stafadmin/1.png" width="600"/>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/stafadmin/2.png" width="600"/>
</p>

<p align="center">
  <img src="https://raw.githubusercontent.com/detrim/img/main/img/stafadmin/3.png" width="600"/>
</p>

---

# 👨‍💻 Author

- Name: Deni
- Role: Laravel Developer
- GitHub: https://github.com/detrim

---

## 📄 License

This project is licensed under the MIT License.
