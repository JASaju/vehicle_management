# Vehicle Management System (Laravel 13 + Livewire PowerGrid)

Modules: **Public landing**, **Dashboard**, **Vehicles**, **Drivers**, **Maintenance**, **Fuel Logs**, **Parking**, and **Reports**.
Every list is a PowerGrid table (search, sort, filters, column toggle, per-page, CSV/XLS export, edit/delete).

## Versions

| Package | Version | Why |
|---|---|---|
| Laravel | ^13.17 | Requires PHP 8.3+ |
| Livewire | ^4.0 | PowerGrid 7 requires Livewire 4 |
| PowerGrid | ^7.0 | Requires Laravel 12+, PHP 8.3+, and Tailwind CSS 4 |
| Tailwind CSS | ^4.1 | Built with Vite and `@tailwindcss/vite` |

## Install

Requirements: PHP 8.3+, Composer 2, Node.js 20.19+ (or 22.12+), and the PHP extensions required by Laravel and mPDF (`curl`, `dom`, `gd`, `mbstring`, `openssl`, and `pdo_mysql` for MySQL).

```bash
# From the extracted vehicle-app project folder
composer install
.env: copy another from localhost  (DB_DATABASE=vehicle_management)
php artisan key:generate
# Edit .env: database, APP_URL, ADMIN_EMAIL, ADMIN_PASSWORD
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

For an existing database, back it up and run `php artisan migrate` (without `--seed`). Do not import
`database/vehicle_management.sql` into an existing database: it drops and recreates application tables.

Open http://127.0.0.1:8000. The public landing page is `/`; the authenticated dashboard is `/dashboard`.
Set `APP_URL` to the canonical public HTTPS origin before submitting the generated sitemap to search engines.

## Notes

- **Styling:** Tailwind CSS 4 and PowerGrid styles are compiled through Vite; run `npm install` and
  `npm run build` after installing PHP dependencies.
- **Login / users.** The landing page is public; the dashboard and operational pages require authentication.
  There is no public registration. The first admin is created during seeding from `ADMIN_EMAIL` and
  `ADMIN_PASSWORD`; use a unique password of at least 12 characters and do not share it. No default
  administrator password is included in the application or SQL files. Admins create users under **Users**.
  Roles: `admin` (everything + Users) and `staff` (everything except Users). Disabled users cannot log in,
  disabled users' existing web sessions are ended on their next request, an admin cannot disable or demote
  themselves, and the last admin cannot be deleted.
  Login is limited to 5 attempts per minute per email + IP.
  - **Database already imported without a `users` table?** Run `database/auth_tables.sql` once (it also adds
    `sessions`, `cache`, `cache_locks`). Already have Laravel's default `users` table? The ALTER statements
    are in the comments at the top of that file, or just run `php artisan migrate` (migration
    `..._000006_prepare_users_table` creates or alters the table).
  - Delete buttons are Livewire methods, so each `deleteRow()` re-checks the login (and admin role for users).
- **SEO.** The public landing page has canonical and social metadata. `/sitemap.xml` lists only `/`, while the
  login and authenticated application pages are marked `noindex`. `/robots.txt` advertises the sitemap using
  `APP_URL`. Robots directives do not provide access control.
- **Baseline browser/session safeguards.** Responses include frame/content-type/referrer/permissions headers.
  Session cookies are secure by default when `APP_ENV=production`; production deployments must use HTTPS.
  Content Security Policy and HSTS are not enabled automatically because the current layouts load Tailwind
  through the production asset pipeline and HTTPS termination is deployment-specific. Laravel 13 JSON
  session serialization is enabled by default; existing sessions will be invalidated during the upgrade.
- **SIEM-ready security logs.** Authentication success/failure, logout, denied authorization, user changes, and
  profile updates are written as JSON to the dedicated daily log channel at
  `storage/logs/security-YYYY-MM-DD.log` (30-day retention by default). Configure `SECURITY_LOG_LEVEL` or
  `SECURITY_LOG_DAYS` in `.env` to adjust the channel. The events exclude passwords, tokens, request bodies,
  query strings, and login identifiers. Admins can inspect the latest 200 normalized events under **Security
  Logs**; the page is read-only and does not expose raw log context. Configure your host SIEM agent/collector
  to ingest the file; this project does not send logs to an external vendor endpoint.
- **VAPT scope.** No VAPT findings were supplied. This project update is not a VAPT assessment; finding-specific
  remediation remains deferred until a report is available.
- **Parking sensor/OCR.** For the optional Arduino Uno + ESP-01 sensor and IP-camera OCR API integration,
  configuration, electrical warnings, endpoint payloads, and firmware are documented in `hardware/README.md`.
  Apply migrations, configure ten slot codes and separate API tokens, and keep the sample HTTP firmware on a
  trusted private LAN only; it is not suitable for public-internet deployment.
- **Business rules built in**
  - Maintenance "In progress" moves the vehicle to *In maintenance*; when no job is in progress it returns to *Active*.
  - Fuel `total_cost` is always liters x price per liter, calculated on the server.
  - Fuel and maintenance entries raise the vehicle odometer (never lower it).
  - Deleting a driver unassigns their vehicles; deleting a vehicle deletes its maintenance and fuel records.
  - Insurance, tax token, fitness and driving-licence dates are coloured: red = expired, yellow = within 30 days.

## Files

```
app/Models/             Vehicle, VehicleType, Driver, Maintenance, FuelLog, ParkingSlot, ParkingRecord, User
app/Livewire/           operational tables and user management (PowerGrid)
app/Http/Controllers/   authentication, dashboard, modules, reports, and public SEO endpoints
app/Http/Middleware/    admin/activity checks and baseline response headers
app/Support/Ui.php      badges, expiry colours, row action buttons
database/migrations/    migrations for core modules, parking, and sensor integration
database/seeders/       DatabaseSeeder (sample data)
database/vehicle_management.sql   plain MySQL schema + sample data
resources/views/        layout, dashboard, index + form view for each module, form components
routes/web.php
```

## Technical documentation

- [System overview and operations](docs/system-documentation.md)
- [Database schema and relationships](docs/database-schema.md)
- [Data-flow diagrams](docs/data-flow-diagram.md)
- [Schema-only MySQL reference](database/vehicle_management_schema.sql)

The schema reference contains DDL only. Use `php artisan migrate` as the authoritative installation and
upgrade mechanism; do not import the sample-data dump into a database that must be preserved.

## PDF reports (mPDF)

Menu: **Reports** (`/reports`). Each report opens as a PDF in a new tab; "Download" saves it.
You can also call them directly; add `?download=1` to force a download.

| Report | URL | Filters |
|---|---|---|
| Vehicle list | `/reports/vehicles` | `status`, `vehicle_type_id` |
| Driver list | `/reports/drivers` | `active` (0 / 1) |
| Maintenance | `/reports/maintenance` | `from`, `to`, `vehicle_id`, `status` |
| Fuel | `/reports/fuel` | `from`, `to`, `vehicle_id` |
| Expiry (insurance, tax token, fitness, licence) | `/reports/expiry` | `days` (default 30) |
| Cost summary per vehicle | `/reports/costs` | `from`, `to` |
| Single vehicle history | `/reports/vehicle/{id}` | none; also a **PDF** button on every row of the Vehicles table |

Code: `app/Services/PdfReport.php` (mPDF wrapper), `app/Http/Controllers/ReportController.php`,
templates in `resources/views/reports/pdf/`. Templates use plain CSS tables because mPDF does not
support Tailwind, flexbox or grid. mPDF needs PHP `mbstring` (and `gd` for images); it writes temp
files to `storage/app/mpdf`, so `storage/` must be writable.

## Parking (ground floor map)

Menu: **Parking**. The map shows every floor row by row, for example *Ground Floor*: row A = A1, A2, A3 and
row B = B1, B2. Each slot is a card:

- **Free** (green): pick a vehicle and press *Park here*.
- **Occupied** (red): shows the vehicle, driver, since when and for how long; *Release* frees the slot.
- **Out of service** (grey): cannot be used.

| Page | URL | What it does |
|---|---|---|
| Map | `/parking` | park and release vehicles |
| Slots | `/parking-slots` | add / edit / delete slots, or create A1..A3 in one go ("Add several slots at once") |
| History | `/parking/history` | every parking stay (vehicle, slot, parked at, released at, duration), export CSV/XLS |
| PDF | `/reports/parking` | current occupancy + history for a date range (also under **Reports**) |

Rules: a slot holds one vehicle at a time and a vehicle can be in one slot at a time (checked inside a database
transaction with row locks, so two people clicking at once cannot double-book). Sold and inactive vehicles
cannot be parked. A slot with a vehicle in it cannot be taken out of service, and a slot that has history cannot
be deleted (take it out of service instead). The Vehicles table has a **Parking** column and the dashboard
shows free / total slots.

Database: `php artisan migrate` (migration `..._000007_create_parking_tables`) or run `database/parking_tables.sql`
once on an existing database (it also inserts the five ground floor slots; sensor-support changes for an existing
database require migration `..._000001_add_parking_sensor_integration`). Parking times use the app timezone:
set `'timezone' => 'Asia/Dhaka'` in `config/app.php` so they show in local time.
