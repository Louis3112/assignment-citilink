# :bangbang: Assignment Test for HCIS Developer Intern :bangbang:

`Backend RESTful API` bagi kandidat magang HCIS dengan membuat `simple E-Learning API` menggunakkan **Laravel Framework**

## :computer: Fitur Utama
- **Security JWT Token Usage:** Pendaftaran, login, dan pengaksesan menggunakkan JWT.
- **ID Usage:** Penggunaan ID untuk pencatatan *user*, *course*, dan *enroll*
- **Role-Based Authentication:** User harus memilih role `tutor` atau `student`, yang dimana akan menentukan fitur dari masing user.
- **Course Management:** *Tutor* dapat membuat, mengedit, dan menghapus *course* mereka sendiri.
- **Enrollment System:** *Tutor* dan *student* dapat mendaftar *courses* yang tersedia.
  - Dengan catatan tidak bisa *enroll* dua kali atau melakukan *self-enroll* (mendaftar *course* sendiri).
- **Report:** *Tutor* dapat melihat daftar *student* yang mendaftar di kursusnya, dan *Student* dapat melihat *course/courses* yang diikuti.
- **Delete Account:** Fitur hapus akun beserta pembersihan data yang terhubung (*cascade*) secara otomasi.
<br>

## :white_check_mark: Pemenuhan Persyaratan
- [x] **Migration**: Skema *database* menggunakan Laravel Migrations dengan tujuan mempermudah proses pembuatan *database*
- [x] **Model**: Menggunakan Eloquent ORM dengan relasi antar *object* (`app\Models\User`, `app\Models\Course`, `app\Models\Enrollment`).
- [x] **Controller**: Logika bisnis yang diatur pada masing-masing *Controller* (`AuthController`, `CourseController`, `EnrollmentController`).
- [x] **Validation**: Validasi menggunakan `$validator` dan *Exception Handling*.
<br>

## :hammer_and_pick: Teknologi yang Digunakan
- **Framework:** Laravel 12.48.1 (API Only)
- **Language:** PHP 8.3.6
- **Database:** PostgreSQL
- **Authentication:** `tymon/jwt-auth`
<br>

## 📥 Cara Instalasi
- Requirement
Pastikan komputer telah terinstal:
1. PHP >= 8.2
2. Composer
3. PostgreSQL

Berikut langkah-langkah untuk menjalankan *project* di lokal:

1. **_Clone Repository_**
   ```bash
   git clone https://github.com/Louis3112/assignment-citilink
   cd assignment-citilink
   ```

2. **_Install Dependencies_**
   ```bash
   composer install
   ```

3. Konfigurasi `.env`
   ```bash
   copy .env.example .env
   ```
   
   Lalu, Buka file `.env` dan sesuaikan konfigurasi database seperti berikut:
   ```bash
   DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=nama_database_anda
    DB_USERNAME=username_postgres_anda
    DB_PASSWORD=password_postgres_anda
   ```

4. **_Generate Application Key_**
   ```bash
   php artisan key:generate
   ```

5. **_Generate JWT Key_**
   ```bash
   php artisan jwt:secret
   ```

6. **Migrasi _Database_**
   ```bash
   php artisan migrate:fresh
   ```

7. **Jalankan _Server_**
   ```bash
   php artisan serve
   ```
    Aplikasi akan berjalan di `http://127.0.0.1:8000`.
<br>

## :books: `Database Design`
Berikut adalah struktur database dengan table dan setiap atributnya
### 1. `users`
 
| Atribut | Tipe Data | Constraints | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `VARCHAR(255)` | **Primary Key** | *Custom id* dengan *prefix* `user-` agar id lebih rapi|
| `name` | `VARCHAR(255)` | | Nama*user* |
| `email` | `VARCHAR(255)` | **Unique** | Email *user*  |
| `password` | `VARCHAR` |  | *Bycrpt* dengan minimal 8 *char* |
| `role` | `ENUM` | Default : `student` | Values: [`student`, `tutor`] |
| `created_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *user* dibuat |
| `updated_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *user* diedit | 

<br>

### 2. `courses`
   
| Atribut | Tipe Data | Constraints | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `VARCHAR(255)` | **Primary Key** | *Custom id* dengan *prefix* `course-` agar id lebih rapi|
| `title` | `VARCHAR(255)` | | Judul *course* |
| `description` | `TEXT` | | Deskripsi *course*
| `created_by` | `VARCHAR(255)` | **Foreign Key** & **Cascade** | (relasi ke `users.id`) |
| `created_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *course* dibuat |
| `updated_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *course* diedit | 

<br>
  
### 3. `enrollments`
   
| Atribut | Tipe Data | Constraints | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `VARCHAR(255)` | **Primary Key** | *Custom id* dengan *prefix* `enroll-` agar id lebih rapi|
| `user_id` | `VARCHAR(255)` | **Foreign Key** & **Cascade** | (relasi ke `users.id`) |
| `course_id` | `VARCHAR(255)` | **Foreign Key** & **Cascade** | (relasi ke `courses.id`) |
| `enrolled_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *user enroll course* tersebut |
| `created_at` | `TIMESTAMP` | Timestamp Laravel | Kapan data dibuat |
| `updated_at` | `TIMESTAMP` | Timestamp Laravel | Kapan data diedit | 

Dengan relasi, 
- Satu `user` dapat mengikuti banyak `course`
- Satu `course` dapat diikuti banyak `user`
- Semua relasi tersebut bertemu (*pivot*) melalui `enrollment`
<br>


## :round_pushpin: Dokumentasi *Endpoint* API
Untuk mempermudah pengujian, saya telah menyertakan Postman Collection dan Environment.

1. **Import ke Postman:**
   - Buka Postman -> Klik tombol **Import**.
   - Arahkan ke folder `/postman` di dalam repository ini.
   - Pilih file `Assignment-Citilink_Collection.json` dan `Assignment_Citilink_Test_Environment.json`.

2. **Setup Environment:**
   - Pastikan environment `Assignment-Citilink_Collection` terpilih di pojok kanan atas Postman.
   - Variabel `port` sudah diset ke `8000`.
   - Lakukan `Run` langsung untuk menguji apakah

### 1. *Auth User*

| Method | Endpoint | Deskripsi | Notes |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/auth/register` | Mendaftar akun baru dengan menentukan role: `student` / `tutor` | **MVP** |
| `POST` | `/api/auth/login` | Login untuk mendapatkan Token JWT | **MVP** |
| `GET` | `/api/auth/me` | Melihat profil data user | New Feat |
| `POST` | `/api/auth/logout` | Melakukan _invalidate_ terhadap token JWT | New Feat |
| `DELETE` | `/api/auth/delete-account` | Menghapus akun user secara permanen beserta data terkait | New Feat|

### 2. *Course*

| Method | Endpoint | Deskripsi | Status |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/courses` | Menampilkan daftar semua `course` | **MVP** |
| `GET` | `/api/courses/{id}` | Menampilkan detail lengkap suatu `course` berdasarkan ID | **MVP** |
| `POST` | `/api/courses` | Membuat `course` baru (Hanya Role `tutor`, `student` tidak bisa membuat `course`) | New Feat |
| `PUT` | `/api/courses/{id}` | Mengupdate data `course` (Hanya pemilik `course`) | New Feat |
| `DELETE` | `/api/courses/{id}` | Menghapus `course` (Hanya pemilik `course`) | New Feat |
| `GET` | `/api/courses/{id}/students` | Melihat daftar siswa yang mendaftar di kursus ini (Hanya pemilik `course`) | New Feat |


### 3. Enrollment System

| Method | Endpoint | Deskripsi | Status |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/courses/{id}/enroll` | `student` mendaftar ke dalam `course` (Mencegah *double enroll* dan *self enroll*) | New Feat |
| `GET` | `/api/my-courses` | Melihat daftar riwayat kursus yang diikuti oleh suatu *user* | New Feat |
