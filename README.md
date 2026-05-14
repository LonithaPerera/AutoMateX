# AutoMateX — Smart Vehicle Management System

> Final Year Project · PUSL3190 · Plymouth University (Sri Lanka) · 2026

AutoMateX is a full-stack web application that helps vehicle owners track fuel usage, service history, maintenance schedules, document expiry, and garage bookings — all in a single, mobile-first dashboard.

---

## Features

### Vehicle Owners
- **My Garage** — manage multiple vehicles with health score ring (mileage, service recency, maintenance status)
- **Fuel Logs** — track fill-ups, cost per litre, and km/L efficiency with trend analysis
- **Service Logs** — full service history with cost tracking and PDF export
- **Trip Logs** — record trips by purpose (personal, business, etc.) with km tracking
- **Maintenance Insights** — AI-style suggestions based on km intervals (oil, tyres, brakes, filters, plugs)
- **Document Expiry Tracker** — insurance, registration, and emission test alerts at 30 / 14 / 7 / 1 days
- **QR Vehicle Pass** — scannable QR code for quick vehicle info sharing
- **Garage Finder and Booking** — search garages by city, view ratings, book appointments
- **Vehicle Export** — print-friendly HTML export of full vehicle history

### Garages
- **Booking Dashboard** — view pending, confirmed, completed, and cancelled appointments
- **Invoice Management** — issue invoices with amount, number, and notes on completed jobs
- **Rating Summary** — see average rating and recent customer reviews
- **Garage Profile** — manage name, address, specialization, working hours, and photo

### Admin
- **User Management** — view all users by role, see last-login timestamps
- **Booking Overview** — full cross-user booking list with status filtering
- **Garage Directory** — view all registered garages
- **Activity Log** — system-wide action audit trail

### Platform
- **Multi-language** — English, Sinhala, Tamil
- **PWA-ready** — installable on mobile with service worker and web manifest
- **In-app Notifications** — real-time bell counter with mark-all-read
- **Double-submit Prevention** — global JS guard on all forms
- **Per-field Inline Validation** — red-border and error text on every form field

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 (PHP 8.2) |
| Frontend | Blade + Tailwind CSS (CDN) |
| Icons | Blade Heroicons (blade-ui-kit/blade-heroicons) |
| QR Codes | chillerlan/php-qrcode |
| Charts | Chart.js (CDN) |
| Database | MySQL (via XAMPP) |
| Auth | Laravel Breeze |
| Testing | PHPUnit 11 — 74 tests, 145 assertions |
| Scheduler | Laravel Task Scheduling (vehicles:check-expiry, bookings:remind) |

---

## Local Setup

### Requirements
- PHP 8.2+
- Composer
- MySQL (XAMPP recommended)

### Installation

```bash
git clone https://github.com/<your-username>/AutoMateX.git
cd AutoMateX

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Environment
Edit `.env` and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=automatex
DB_USERNAME=root
DB_PASSWORD=
```

### Database and Demo Data
```bash
# Create the database in MySQL first, then:
php artisan migrate:fresh --seed
```

This seeds the database with realistic demo data — see Demo Accounts below.

### Run
```bash
php artisan serve
# Visit: http://localhost:8000
```

### Storage Link (for uploaded images)
```bash
php artisan storage:link
```

---

## Demo Accounts

All passwords are ready to use immediately after seeding.

| Role | Name | Email | Password |
|------|------|-------|----------|
| Admin | Ashan Perera | admin@automatex.lk | Admin@1234 |
| Vehicle Owner | Kavindu Rajapaksa | kavindu@gmail.com | Owner@1234 |
| Vehicle Owner | Dilani Wickramasinghe | dilani@gmail.com | Owner@1234 |
| Garage | Speedy Motors Auto Service | speedy@garage.lk | Garage@1234 |
| Garage | AutoCare Service Centre | autocare@garage.lk | Garage@1234 |

**Kavindu** has 4 vehicles (Toyota Aqua, Honda Vezel, Toyota Prius, Mazda Demio) with full fuel logs, service histories, and realistic overdue/due-soon maintenance alerts.

**Dilani** has 4 vehicles (Suzuki Swift, Nissan Leaf, Honda Fit, Toyota Vitz) with similar depth.

Both garages have bookings at various statuses (pending, confirmed, completed, cancelled). Completed bookings include invoices.

---

## Running Tests

```bash
php artisan test
```

Expected: **74 tests, 145 assertions, 0 failures**

Test coverage includes: authentication, vehicle CRUD, fuel logs, service logs, trip logs, garage bookings, notifications, parts search, admin access, and the suggestion engine.

---

## Scheduled Commands

| Command | Schedule | Purpose |
|---------|----------|---------|
| `php artisan vehicles:check-expiry` | Daily 09:00 | In-app notifications for document expiry (insurance, registration, emission) at 30/14/7/1/0 days |
| `php artisan bookings:remind` | Daily 08:00 | Booking reminder notifications to vehicle owners |

To run the scheduler locally:
```bash
php artisan schedule:run
```

---

## Project Structure

```
app/
  Console/Commands/       Artisan scheduled commands
  Http/Controllers/       All feature controllers
  Models/                 Eloquent models
database/
  migrations/             All table migrations
  seeders/                DatabaseSeeder with full demo data
lang/
  en/ si/ ta/             English, Sinhala, Tamil translations
resources/views/
  vehicles/               My Garage, vehicle show/edit/archived
  service/ fuel/ trips/   Log views
  garages/                Garage finder, detail, booking
  dashboard.blade.php     Role-aware dashboard
  layouts/app.blade.php   Main authenticated layout
routes/
  web.php                 All named routes
  console.php             Scheduler registration
tests/Feature/            74 PHPUnit feature tests
```

---

## License

Developed as an academic final year project for PUSL3190 at the University of Plymouth (Sri Lanka Institute of Information Technology). Not intended for commercial use.
