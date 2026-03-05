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
| 🚗 Vehicle Management | Add, view, and manage multiple vehicles |
| ⛽ Fuel Tracking | Log fuel purchases, calculate km/L efficiency |
| 🔧 Service History | Track all maintenance and repair records |
| 🧠 Suggestion Engine | Rule-based engine predicts next service due by mileage |
| 📱 QR Code Generation | Unique QR per vehicle — scan to view service history publicly |
| 🔩 Parts Verification DB | OEM part numbers for 5 vehicle models to fight counterfeits |
| 🏪 Garage Booking | Browse garages, book appointments, receive invoices |
| 👨‍💼 Admin Dashboard | System-wide stats and user management |
| 📲 PWA Support | Installable on mobile, works offline with service worker |

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| Laravel 12 (PHP) | Backend framework |
| MySQL | Database |
| Bootstrap 5 / Tailwind CSS | Frontend styling |
| Laravel Breeze | Authentication scaffolding |
| chillerlan/php-qrcode | QR code generation |
| PHPUnit | Automated testing |
| Service Worker + Web Manifest | PWA functionality |

---

## 👥 User Roles

| Role | Access |
|---|---|
| `vehicle_owner` | Manage vehicles, fuel logs, service history, bookings |
| `garage` | Register garage, manage bookings, issue invoices |
| `admin` | View system stats, manage all users |

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
php artisan migrate
php artisan db:seed --class=MaintenanceScheduleSeeder
php artisan db:seed --class=PartsSeeder
```

**7. Start the server**
```bash
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---

## 🧪 Running Tests
```bash
php artisan test
```

Expected output:
```
Tests: 48 passed (101 assertions)
```

### Test Coverage

| Test Suite | Tests |
|---|---|
| AuthTest | 5 tests |
| VehicleTest | 5 tests |
| FuelLogTest | 2 tests |
| ServiceLogTest | 2 tests |
| SuggestionTest | 2 tests |
| PartsTest | 3 tests |
| GarageBookingTest | 4 tests |

---

## 🗄️ Database Schema

| Table | Purpose |
|---|---|
| `users` | User accounts with roles |
| `vehicles` | Vehicle records per user |
| `fuel_logs` | Fuel purchase and efficiency logs |
| `service_logs` | Service and repair history |
| `maintenance_schedules` | Rule-based suggestion engine data |
| `parts` | OEM spare parts database |
| `garages` | Registered garage profiles |
| `bookings` | Service appointment bookings |

---

## 📱 PWA Features

- ✅ Installable on Android and iOS home screen
- ✅ Service Worker caches key pages for offline access
- ✅ Web App Manifest with custom icons and theme
- ✅ Offline fallback page when no connection available

---

## 🔩 Parts Verification Database

Covers **5 vehicle models** with OEM part numbers:

| Make | Model | Parts Covered |
|---|---|---|
| Toyota | Vitz | Oil Filter, Air Filter, Spark Plug, Brake Pads, Drive Belt |
| Toyota | Premio | Oil Filter, Air Filter, Spark Plug, Brake Pads, Cabin Filter |
| Toyota | Aqua | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Suzuki | Alto | Oil Filter, Air Filter, Spark Plug, Brake Pads |
| Honda | Fit | Oil Filter, Air Filter, Spark Plug, Brake Pads |

---

## 📂 Project Structure
```
AutoMateX/
├── app/
│   ├── Http/Controllers/     # All controllers
│   ├── Models/               # Eloquent models
│   └── Http/Middleware/      # AdminMiddleware
├── database/
│   ├── migrations/           # All table migrations
│   ├── seeders/              # MaintenanceSchedule & Parts seeders
│   └── factories/            # Vehicle & Garage factories
├── public/
│   ├── manifest.json         # PWA manifest
│   ├── sw.js                 # Service Worker
│   └── icons/                # PWA icons
├── resources/views/
│   ├── vehicles/             # Vehicle views
│   ├── fuel/                 # Fuel log views
│   ├── service/              # Service history views
│   ├── suggestions/          # Suggestion engine views
│   ├── qrcode/               # QR code views
│   ├── parts/                # Parts DB views
│   ├── garages/              # Garage views
│   ├── bookings/             # Booking views
│   └── admin/                # Admin dashboard views
├── routes/
│   └── web.php               # All application routes
└── tests/
    └── Feature/              # 48 automated feature tests
```

---

## 🔗 GitHub Repository

[https://github.com/LonithaPerera/AutoMateX](https://github.com/LonithaPerera/AutoMateX)

---

## 📄 License

This project is developed for academic purposes as part of PUSL3190 Computing Project
at Plymouth University (NSBM Green University, Sri Lanka).