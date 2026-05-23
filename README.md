# AutoMateX 🚗
### Web-Based Vehicle Service & Maintenance Management System

**Student:** Bethmage Lonitha Perera  
**Index Number:** 10952829  
**Supervisor:** Ms. Thilini Bakmeedeniya  
**Module:** PUSL3190 — Computing Project  
**University:** Plymouth University (Administered by NSBM Green University)

---

## 📋 Project Overview

AutoMateX is a Progressive Web Application (PWA) built with Laravel that helps
vehicle owners in Sri Lanka manage their vehicle maintenance, track service
history, verify spare parts, and book garage appointments — all in one place.

### 🎯 Problem Statement
Sri Lankan vehicle owners struggle with:
- No centralised system to track vehicle maintenance history
- Difficulty predicting when next service is due
- Counterfeit spare parts with no way to verify OEM numbers
- No easy way to book garage appointments online

### ✅ Solution
AutoMateX addresses all four problems with a unified web platform accessible
on any device.

---

## 🚀 Features

| Module | Description |
|---|---|
| 🔐 Authentication | Register, login, logout with role-based access |
| 🚗 Vehicle Management | Add, view, archive and manage multiple vehicles with health score |
| ⛽ Fuel Tracking | Log fuel purchases, calculate km/L efficiency with trend analysis |
| 🔧 Service History | Track all maintenance and repair records with PDF export |
| 🧭 Trip Log | Record trips by purpose (personal/business) with km tracking and PDF export |
| 🧠 Suggestion Engine | Rule-based engine predicts next service due by mileage |
| 📄 Document Expiry Tracker | Alerts for insurance, registration and emission test expiry at 30/14/7/1/0 days |
| 📱 QR Code Generation | Unique QR per vehicle — scan to view service history publicly |
| 🔩 Parts Verification DB | OEM part numbers for 8 vehicle models to fight counterfeits |
| 🏪 Garage Booking | Browse garages, book appointments, receive invoices, leave ratings |
| 🔔 In-App Notifications | Real-time bell counter with notification history and mark-all-read |
| 👨‍💼 Admin Dashboard | System-wide stats, user management, and activity log |
| 📲 PWA Support | Installable on mobile, works offline with service worker |
| 🌐 Multi-Language | Full support for English, Sinhala and Tamil |

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| Laravel 12 (PHP 8.2) | Backend framework |
| MySQL | Database |
| Tailwind CSS | Frontend styling |
| Laravel Breeze | Authentication scaffolding |
| Blade Heroicons | Icon library |
| chillerlan/php-qrcode | QR code generation |
| Chart.js | Spending and mileage charts |
| Mailtrap (SMTP) | Email notifications for bookings |
| PHPUnit | Automated testing |
| Service Worker + Web Manifest | PWA functionality |

---

## 👥 User Roles

| Role | Access |
|---|---|
| `vehicle_owner` | Manage vehicles, fuel logs, service history, trip logs, bookings, document expiry |
| `garage` | Register garage, manage bookings, issue invoices, view ratings |
| `admin` | View system stats, manage all users, view activity log |

---

## ⚙️ Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- MySQL
- Node.js & npm
- XAMPP (or any local server)

### Steps

**1. Clone the repository**

```bash
git clone https://github.com/LonithaPerera/AutoMateX.git
cd AutoMateX
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install Node dependencies**

```bash
npm install
npm run build
```

**4. Configure environment**

```bash
cp .env.example .env
php artisan key:generate
```

**5. Set up database**

Edit `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=automatex
DB_USERNAME=root
DB_PASSWORD=
```

**6. Run migrations and seeders**

```bash
php artisan migrate:fresh --seed
```

**7. Create storage link**

```bash
php artisan storage:link
```

**8. Start the server**

```bash
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---

## 🔑 Demo Accounts

| Role | Name | Email | Password |
|---|---|---|---|
| Admin | Ashan Perera | admin@automatex.lk | Admin@1234 |
| Vehicle Owner | Kavindu Rajapaksa | kavindu@gmail.com | Owner@1234 |
| Vehicle Owner | Dilani Wickramasinghe | dilani@gmail.com | Owner@1234 |
| Garage | Speedy Motors Auto Service | speedy@garage.lk | Garage@1234 |
| Garage | AutoCare Service Centre | autocare@garage.lk | Garage@1234 |

---

## 🧪 Running Tests

```bash
php artisan test
```

Expected output:

```
Tests: 74 passed (145 assertions)
```

### Test Coverage

| Test Suite | Tests |
|---|---|
| AdminTest | 7 tests |
| AuthTest | 5 tests |
| VehicleTest | 8 tests |
| FuelLogTest | 4 tests |
| ServiceLogTest | 4 tests |
| TripLogTest | 5 tests |
| SuggestionTest | 2 tests |
| PartsTest | 4 tests |
| GarageBookingTest | 6 tests |
| NotificationTest | 4 tests |
| ProfileTest | 5 tests |
| Auth Suite (login/register/reset/verify) | 18 tests |

---

## 🗄️ Database Schema

| Table | Purpose |
|---|---|
| `users` | User accounts with roles |
| `vehicles` | Vehicle records per user |
| `fuel_logs` | Fuel purchase and efficiency logs |
| `service_logs` | Service and repair history |
| `trip_logs` | Trip records with purpose and km |
| `maintenance_schedules` | Rule-based suggestion engine data |
| `parts` | OEM spare parts database |
| `garages` | Registered garage profiles |
| `bookings` | Service appointment bookings |
| `ratings` | Garage ratings submitted by vehicle owners |
| `app_notifications` | In-app notification records |
| `push_subscriptions` | PWA push notification subscriptions |
| `activity_logs` | System-wide admin audit trail |

---

## 📱 PWA Features

- ✅ Installable on Android and iOS home screen
- ✅ Service Worker caches key pages for offline access
- ✅ Web App Manifest with custom icons and theme
- ✅ Offline fallback page when no connection available

---

## 🔩 Parts Verification Database

Covers **8 vehicle models** with OEM part numbers:

| Make | Model | Parts Covered |
|---|---|---|
| Toyota | Aqua | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Honda | Vezel | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Toyota | Prius | Oil Filter, Air Filter, Spark Plug, Brake Pads, Cabin Filter |
| Mazda | Demio | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Suzuki | Swift | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Nissan | Leaf | Oil Filter, Air Filter, Brake Pads, Cabin Filter |
| Honda | Fit | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Toyota | Vitz | Oil Filter, Air Filter, Spark Plug, Brake Pads, Drive Belt |

---

## 📂 Project Structure

```
AutoMateX/
├── app/
│   ├── Console/Commands/     # Scheduled Artisan commands
│   ├── Http/Controllers/     # All controllers
│   ├── Models/               # Eloquent models
│   └── Http/Middleware/      # AdminMiddleware
├── database/
│   ├── migrations/           # All table migrations
│   ├── seeders/              # DatabaseSeeder, MaintenanceSchedule & Parts seeders
│   └── factories/            # FuelLog & ServiceLog factories
├── lang/
│   ├── en/                   # English translations
│   ├── si/                   # Sinhala translations
│   └── ta/                   # Tamil translations
├── public/
│   ├── manifest.json         # PWA manifest
│   ├── sw.js                 # Service Worker
│   └── icons/                # PWA icons
├── resources/views/
│   ├── vehicles/             # Vehicle views
│   ├── fuel/                 # Fuel log views
│   ├── service/              # Service history views
│   ├── trips/                # Trip log views
│   ├── suggestions/          # Suggestion engine views
│   ├── qrcode/               # QR code views
│   ├── parts/                # Parts DB views
│   ├── garages/              # Garage views
│   ├── bookings/             # Booking views
│   ├── errors/               # Custom error pages (404, 419, 500)
│   └── admin/                # Admin dashboard views
├── routes/
│   ├── web.php               # All application routes
│   └── console.php           # Scheduled command registration
└── tests/
    └── Feature/              # 74 automated feature tests
```

---

## 🔗 GitHub Repository

[https://github.com/LonithaPerera/AutoMateX](https://github.com/LonithaPerera/AutoMateX)

---

## 📄 License

This project is developed for academic purposes as part of PUSL3190 Computing Project
at Plymouth University (NSBM Green University, Sri Lanka).
