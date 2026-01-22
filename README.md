# :bangbang: Assignment Test for HCIS Developer Intern :bangbang:

`Backend RESTful API` bagi kandidat magang HCIS dengan membuat `simple E-Learning API` menggunakkan **Laravel Framework**

## :books: 1. `Database Design`
Berikut adalah struktur database dengan table dan setiap atributnya
1. `users`
 
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

2. `courses`
   
| Atribut | Tipe Data | Constraints | Notes |
| :--- | :--- | :--- | :--- |
| `id` | `VARCHAR(255)` | **Primary Key** | *Custom id* dengan *prefix* `course-` agar id lebih rapi|
| `title` | `VARCHAR(255)` | | Judul *course* |
| `description` | `TEXT` | | Deskripsi *course*
| `created_by` | `VARCHAR(255)` | **Foreign Key** & **Cascade** | (relasi ke `users.id`) |
| `created_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *course* dibuat |
| `updated_at` | `TIMESTAMP` | Timestamp Laravel | Kapan *course* diedit | 

<br>
  
3. `enrollments`
   
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
  
## :computer: Fitur Utama
- **Security JWT Token Usage:** Pendaftaran, login, dan pengaksesan menggunakkan JWT.
- **ID Usage:** Penggunaan ID untuk pencatatan *user*, *course*, dan *enroll*
- **Role-Based Authentication:** User harus memilih role `tutor` atau `student`, yang dimana akan menentukan fitur dari masing user.
- **Course Management:** *Tutor* dapat membuat, mengedit, dan menghapus *course* mereka sendiri.
- **Enrollment System:** *Tutor* dan *student* dapat mendaftar *courses* yang tersedia.
  - Dengan catatan tidak bisa *enroll* dua kali atau melakukan *self-enroll* (mendaftar *course* sendiri).
- **Report:** *Tutor* dapat melihat daftar *student* yang mendaftar di kursusnya, dan *Student* dapat melihat *course/courses* yang diikuti.
- **Delete Account:** Fitur hapus akun beserta pembersihan data yang terhubung (*cascade*) secara otomasi.

## :hammer_and_pick: Teknologi yang Digunakan
- **Framework:** Laravel 12.48.1 (API Only)
- **Language:** PHP 8.3.6
- **Database:** PostgreSQL
- **Authentication:** `tymon/jwt-auth`

## :white_check_mark: Pemenuhan Persyaratan
- [x] **Migration**: Skema *database* menggunakan Laravel Migrations dengan tujuan mempermudah proses pembuatan *database*
- [x] **Model**: Menggunakan Eloquent ORM dengan relasi antar *object* (`app\Models\User`, `app\Models\Course`, `app\Models\Enrollment`).
- [x] **Controller**: Logika bisnis yang diatur pada masing-masing *Controller* (`AuthController`, `CourseController`, `EnrollmentController`).
- [x] **Validation**: Validasi menggunakan `$validator` dan *Exception Handling*.
