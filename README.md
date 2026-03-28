# Vehicle Booking System

Aplikasi web untuk manajemen pemesanan kendaraan dengan sistem approval berjenjang, monitoring penggunaan kendaraan, serta pencatatan BBM dan service.

---

## Login Account

| Role | Email | Password |

| -------- | ----------------- | -------- |

| Admin | admin@test.com | 123456 |

| Approver | approver1@test.com | 123456 |

| Approver | approver2@test.com | 123456 |

---

## Tech Stack

- PHP: PHP 8.3 or higher
- Framework: Laravel 12
- Database: MySQL / MariaDB (testing)
- Frontend: Blade, Tailwind CSS, Alpine.js, Chart.js
- Queue/Notification: Database Notification
- Testing: PHPUnit

---

## Installation

```bash
git clone <repo-url>
cd project

composer install
npm install
npm run build

cp .env.example .env
php artisan key:generate
```

````

---

## Database Setup

```
php artisan migrate
php artisan db:seed
```

---

## Run Application

```bash
composer run dev
```

Akses:

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

<iframe style="border:none" width="800" height="450" src="https://whimsical.com/embed/EMwq6973GJxxY1awSvEjVS"></iframe>

---

## Physical Data Model

<iframe width="560" height="315" src="https://dbdiagram.io/e/69c563b9fb2db18e3b133428/69c563bffb2db18e3b1334d1"></iframe>

---

## Testing

Menjalankan semua test:

```bash
php artisan test
```

Menjalankan test tertentu:

```bash
php artisan test --filter=BookingFlowTest
php artisan test --filter=ApprovalLevelOneTest
php artisan test --filter=ApprovalLevelTwoTest
php artisan test --filter=VehicleUsageTest
php artisan test --filter=EndToEndFlowTest
```

---

## Architecture

Aplikasi ini menggunakan pendekatan:

- Service Layer Pattern (Business logic dipisah dari controller)
- Form Request Validation
- Database Transaction untuk menjaga konsistensi data
- Notification System untuk komunikasi antar role
- Activity Logging untuk audit trail

---

## Notes

- Semua proses utama (create booking, approval, usage) tercatat di activity_logs
- Approval bersifat sequential (level 1 harus approve sebelum level 2)
- Notifikasi dikirim menggunakan database notification
- Sistem dirancang modular untuk memudahkan pengembangan lebih lanjut

---
````
