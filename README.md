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
> Untuk mempermudah pengujian, saya telah menyertakan Postman *Collection* dan *Environment*.
1. **Import ke Postman:**
   - Buka Postman -> Klik tombol **Import**.
   - Pilih import folder.
   - Arahkan ke folder `/postman` di dalam repository ini.
   - Maka Postman akan secara otomatis melakukan import terhadap *Collection* dan *Environment*.

2. **Setup Environment:**
   - Pastikan environment `Assignment Citilink Test Environment` terpilih di pojok kanan atas Postman.
   - Variabel `port` sudah diset ke `8000`.
   - Lakukan `Run` langsung untuk menguji *backend*.

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
<br>

## :pencil2: Catatan Developer
Catatan ini merangkum proses belajar dan langkah teknis yang dilakukan selama mengerjakan *assignment test* untuk posisi HCIS Developer Intern di Citilink.
### 0. Fase Pembelajaraan 

#### Cerita Saya
<div align="justify">
&emsp;&emsp;Ketika pertama kali menerima tugas ini pada Hari Selasa, 20 Januari 2026, 13:25. Saya segera mempersiapkan <i>youtube</i> dan <i>notes</i> saya untuk kembali mengulas materi mengenai Laravel.
Saya segera menonton playlist <a href="https://www.youtube.com/watch?v=T1TR-RGf2Pw&list=PLFIM0718LjIW1Xb7cVj7LdAr32ATDQMdr">Belajar Laravel</a> yang dibuat oleh WPU, Pak Sandhika Galih.

&emsp;&emsp;Saya berusaha untuk memahami bagian-bagian penting dari Laravel, seperti penggunaan <i>Database</i> dan <i>Migration</i>, <i>MVC</i> (tapi yang dibutuhkan saat ini adalah <i>Model</i> dan <i>Controller</i>), <i>Eloquent ORM</i>, dan penggunaan JWT.
Untungnya, karena adanya pemahaman dasar mengenai <i>back-end</i> pada JavaScript (khususnya Hapi.js), saya menemukan banyak kesamaan konsep arsitektur dengan Laravel. Maka hal ini mempercepat proses adaptasi saya.

&emsp;&emsp;Sehingga, Rabu, 21 Januari 2026, saya mulai mengerjakan tugas ini.
</div>
<br>

### 1. Fase Perencanaan 
#### Analisis *Requirement*
Saya memahami bahwa tugas ini memerlukan setidaknya 3 struktur *database*
- `users` : untuk menyimpan data pengguna
- `courses`: untuk menyimpan data *courses* yang dibuat oleh pengguna
- `enrollments`: untuk menjadi tabel pivot yang menghubungkan `users` dan `courses`.

memerlukan setidaknya 4 *endpoint* wajib yang harus tersedia
- `POST register user`
- `POST login user`
- `GET List Course`
- `GET Detail Course`

serta wajib menggunakkan
- *Database migration*
- *Model*
- *Controller*
- *Validation*

Maka, saya mengambil beberapa langkah krusial untuk bisa mengembangkan aplikasi ini.
1. Penggunaan JWT untuk autentikasi *login* dan *CRUD* `courses`.
2. Penggunaan *custom* ID (contoh: `user-xxx`, `enroll-xxx`) agar terlihat lebih rapi dibandingkan *auto-increment* (Saya menyadari bahwa ini adalah fitur dari Laravel).
3. `users`
   - `users` dapat memilih *role* (`student` atau `tutor`) yang diinginkan.
   - `users` dapat melihat semua daftar `course`.
   - `users` dapat melihat suatu `course` secara detail.
   - `users` dapat melihat profil data user.
   - `users` dapat melakukan *logout* serta menghapus akun jika diperlukan.
     
4. `courses`
   - `courses` hanya dapat dibuat oleh `users` dengan *role* `tutor`.
   - `courses` menerapkan validasi kepemilikan (*ownership*), di mana hanya pembuat *course* tersebut yang mengubah (*update*) atau menghapus (*delete*) datanya.
   - `courses` dapat melihat daftar siswa yang telah mendaftar di kelas mereka (*reporting*).

5. `enrollments`
   - `enrollments` berfungsi sebagai *pivot* antara `student` dan `courses`.
   - `users` dengan *role* `student` dapat melakukan pendaftaran (*enroll*) pada `course` yang tersedia.
   - Sistem memiliki validasi logis untuk mencegah duplikasi, sehingga `student` tidak dapat mendaftar dua kali pada `course` yang sama.
   - `student` dapat melihat daftar riwayat `course` yang sedang mereka ikuti (*My Courses*).
   - `tutor` tidak dapat mendaftar `course` nya sendiri.
<br>
    
### 2. Fase Implementasi 
#### Setup dan Pengerjaan Project
- Dengan Instalasi Laravel, konfigurasi PostgreSQL, dan setup JWT Auth.
- Implementasi `AuthController` untuk menangani token JWT.
- Membuat file migration sesuai desain DB dan Model dengan relasi Eloquent (`hasMany`, `belongsToMany`).
- Membuat *controller* berdasarkan masing-masing Model.
- Mengadopsi standar *Resource Controller* (`index`, `store`, `show`, `update`, `destroy`) untuk membiasakan diri terhadap standar penamaan Laravel.
- Menambahkan logika *validation* untuk ownership dan privasi *user*
- Pembuatan *endpoint* berdasarkan analisis *requirement* dan tambahan fitur.
- Pembuatan *unit testing* dengan menggunakkan Postman API untuk mengetahui aplikasi dapat berjalan dengan baik.
<br>

### 3. Tantangan selama pengerjaan
Selama pengerjaan, beberapa tantangan yang saya terima:
- Penggunaan folder yang cukup ketat dan banyak apabila dibandingkan dengan *framework* JavaScript. 
- Memahami konvensi penamaan *Resource Controller* bawaan Laravel (`index`, `store`, `update`, dll.) yang sebelumnya tidak saya temukan dalam JavaScript .
- Penggunaan *Eloquent ORM* dan relasi antar model, karena sebelumnya saya menggunakkan *Raw SQL* pada JavaScript.
<br>

Tetapi, semua proses tersebut saya terima dan saya sangat senang bisa mempelajari kembali materi ini lebih dalam.
- Cornelius Louis Nathan
