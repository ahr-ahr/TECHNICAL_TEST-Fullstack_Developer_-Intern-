# Vehicle Booking System

Aplikasi web untuk manajemen pemesanan kendaraan dengan sistem approval berjenjang, monitoring penggunaan kendaraan, serta pencatatan BBM dan service.

---

## Login Account

| Role | Email | Password |

|----------|--------------------|----------|

| Admin | admin@test.com | 123456 |

| Approver | approver1@test.com | 123456 |

| Approver | approver2@test.com | 123456 |

---

## Tech Stack

- PHP: >= 8.3
- Framework: Laravel 12
- Database: MySQL / MariaDB
- Frontend: Blade, Tailwind CSS, Alpine.js, Chart.js
- Queue/Notification: Database Notification
- Testing: PHPUnit

---

## Installation

Clone repository:

```bash
git clone https://github.com/ahr-ahr/TECHNICAL_TEST-Fullstack_Developer_-Intern-.git
```

Masuk ke folder project:

```bash
cd TECHNICAL_TEST-Fullstack_Developer_-Intern-
```

Install dependency backend:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Build asset (optional):

```bash
npm run build
```

Setup environment:

```bash
cp .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

---

## Database Setup

Jalankan migration:

```bash
php artisan migrate
```

Jalankan seeder:

```bash
php artisan db:seed
```

---

## Run Application

Jalankan server:

```bash
composer run dev
```

Akses di browser:

```
http://127.0.0.1:8000
```

---

## Features

- Booking kendaraan oleh admin
- Penentuan driver dan approver
- Approval berjenjang (Level 1 dan Level 2)
- Notifikasi setiap proses approval
- Monitoring penggunaan kendaraan
- Input vehicle usage (KM awal & akhir)
- Input fuel logs (BBM)
- Input service logs
- Activity log untuk setiap proses
- Dashboard statistik penggunaan kendaraan
- Export laporan ke Excel dan PDF

---

## Workflow

1. Admin membuat booking kendaraan
2. Sistem membuat approval level 1 dan 2
3. Approver level 1 melakukan approval
4. Jika disetujui, dilanjut ke level 2
5. Approver level 2 melakukan approval
6. Jika disetujui:
    - Booking menjadi approved
    - Kendaraan dapat digunakan
7. Admin memulai penggunaan kendaraan
8. Admin menginput:
    - Vehicle usage (KM)
    - Fuel logs
    - Service logs
9. Booking selesai (completed)

---

## Activity Diagram

Lihat di sini:
[https://whimsical.com/EMwq6973GJxxY1awSvEjVS](https://whimsical.com/EMwq6973GJxxY1awSvEjVS)

---

## Physical Data Model

Lihat di sini:
[https://dbdiagram.io/e/69c563b9fb2db18e3b133428](https://dbdiagram.io/e/69c563b9fb2db18e3b133428)

---

## Testing

Menjalankan semua test:

```bash
php artisan test
```

Menjalankan test tertentu:

```bash
php artisan test --filter=BookingFlowTest
```

```bash
php artisan test --filter=ApprovalLevelOneTest
```

```bash
php artisan test --filter=ApprovalLevelTwoTest
```

```bash
php artisan test --filter=VehicleUsageTest
```

```bash
php artisan test --filter=EndToEndFlowTest
```

---

## Architecture

Aplikasi ini menggunakan pendekatan:

- Service Layer Pattern (business logic dipisah dari controller)
- Form Request Validation
- Database Transaction untuk menjaga konsistensi data
- Notification System untuk komunikasi antar role
- Activity Logging untuk audit trail

---

## Notes

- Semua proses utama tercatat di `activity_logs`
- Approval bersifat sequential (level 1 harus approve sebelum level 2)
- Notifikasi menggunakan database notification
- Sistem dirancang modular untuk pengembangan lebih lanjut

```

```
