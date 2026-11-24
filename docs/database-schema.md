# Database Schema

## Source of truth

The Laravel migrations under `database/migrations` define the current application schema. Run
`php artisan migrate` for new installations and upgrades. `database/vehicle_management_schema.sql` is a
schema-only MySQL reference for the business tables; it has no `INSERT` or `DROP` statements and does not
replace migrations or Laravel's migration ledger.

The SQL dump supplied separately is not the current schema: it predates the parking sensor migration. In
particular, it does not contain the current sensor/plate columns on `parking_slots` and requires
`parking_records.vehicle_id`, while the current schema permits an unidentified sensor-detected vehicle. The
dump also contains row data; do not copy it into a schema reference or import it over a database you need to
preserve.

## Entity relationship diagram

```mermaid
erDiagram
    VEHICLE_TYPES ||--o{ VEHICLES : classifies
    DRIVERS o|--o{ VEHICLES : assigned_to
    VEHICLES ||--o{ MAINTENANCES : has
    VEHICLES ||--o{ FUEL_LOGS : consumes
    DRIVERS o|--o{ FUEL_LOGS : operates
    PARKING_SLOTS ||--o{ PARKING_RECORDS : contains
    VEHICLES o|--o{ PARKING_RECORDS : identified_as

    VEHICLE_TYPES {
        bigint id PK
        varchar name UK
        timestamp created_at
        timestamp updated_at
    }
    DRIVERS {
        bigint id PK
        varchar name
        varchar phone
        varchar email
        varchar license_number UK
        date license_expiry
        varchar address
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }
    VEHICLES {
        bigint id PK
        bigint vehicle_type_id FK
        bigint driver_id FK
        varchar registration_no UK
        varchar brand
        varchar model
        smallint year
        varchar fuel_type
        varchar status
        int odometer
    }
    MAINTENANCES {
        bigint id PK
        bigint vehicle_id FK
        varchar type
        date service_date
        date next_service_date
        decimal cost
        varchar status
    }
    FUEL_LOGS {
        bigint id PK
        bigint vehicle_id FK
        bigint driver_id FK
        date fuel_date
        decimal liters
        decimal price_per_liter
        decimal total_cost
        int odometer
    }
    PARKING_SLOTS {
        bigint id PK
        varchar floor
        varchar code
        boolean is_active
        boolean sensor_occupied
        datetime sensor_updated_at
        varchar last_detected_plate
    }
    PARKING_RECORDS {
        bigint id PK
        bigint parking_slot_id FK
        bigint vehicle_id FK
        varchar detected_plate
        varchar source
        datetime parked_at
        datetime released_at
    }
}
```

`o|` on `PARKING_RECORDS.vehicle_id` represents the optional vehicle link: a sensor can report occupancy
before the vehicle is identified. Laravel support/authentication tables are not shown in this business ERD.

## Business tables

All tables use a bigint unsigned auto-increment primary key and nullable `created_at`/`updated_at` timestamps
unless specified otherwise.

### `vehicle_types`

| Column | Type | Constraints / meaning |
|---|---|---|
| `name` | varchar(100) | Required, unique |

### `drivers`

| Column | Type | Constraints / meaning |
|---|---|---|
| `name` | varchar(150) | Required |
| `phone` | varchar(30) | Required |
| `email` | varchar(150) | Nullable |
| `license_number` | varchar(60) | Required, unique |
| `license_expiry` | date | Required |
| `address` | varchar(255) | Nullable |
| `is_active` | boolean | Required, default `true` |

### `vehicles`

| Column | Type | Constraints / meaning |
|---|---|---|
| `vehicle_type_id` | bigint unsigned | Required FK to `vehicle_types`; restrict deletion |
| `driver_id` | bigint unsigned | Nullable FK to `drivers`; set null on driver deletion |
| `registration_no` | varchar(50) | Required, unique |
| `brand`, `model` | varchar(80) each | Required |
| `year` | smallint unsigned | Required |
| `color` | varchar(40) | Nullable |
| `chassis_no`, `engine_no` | varchar(80) each | Nullable |
| `fuel_type` | enum | `petrol`, `diesel`, `octane`, `cng`, `lpg`, `electric`, `hybrid`; default `petrol` |
| `seat_capacity` | tinyint unsigned | Required, default `4` |
| `odometer` | int unsigned | Required, default `0` |
| `purchase_date` | date | Nullable |
| `purchase_price` | decimal(12,2) | Nullable |
| `insurance_expiry`, `tax_token_expiry`, `fitness_expiry` | date | Nullable |
| `status` | enum | `active`, `in_maintenance`, `inactive`, `sold`; indexed, default `active` |
| `notes` | text | Nullable |

### `maintenances`

| Column | Type | Constraints / meaning |
|---|---|---|
| `vehicle_id` | bigint unsigned | Required FK to `vehicles`; cascade on vehicle deletion |
| `type` | enum | `oil_change`, `tire`, `brake`, `battery`, `general_service`, `repair`, `other`; default `general_service` |
| `description` | varchar(255) | Nullable |
| `service_date` | date | Required, indexed |
| `next_service_date` | date | Nullable |
| `odometer` | int unsigned | Nullable |
| `cost` | decimal(12,2) | Required, default `0.00` |
| `workshop` | varchar(150) | Nullable |
| `status` | enum | `scheduled`, `in_progress`, `completed`; default `completed` |

### `fuel_logs`

| Column | Type | Constraints / meaning |
|---|---|---|
| `vehicle_id` | bigint unsigned | Required FK to `vehicles`; cascade on vehicle deletion |
| `driver_id` | bigint unsigned | Nullable FK to `drivers`; set null on driver deletion |
| `fuel_date` | date | Required, indexed |
| `liters`, `price_per_liter` | decimal(8,2) each | Required |
| `total_cost` | decimal(12,2) | Required; calculated by the application |
| `odometer` | int unsigned | Nullable |
| `station` | varchar(150) | Nullable |

### `parking_slots`

| Column | Type | Constraints / meaning |
|---|---|---|
| `floor` | varchar(50) | Required, default `Ground Floor` |
| `zone` | varchar(5) | Required |
| `number` | smallint unsigned | Required |
| `code` | varchar(12) | Required; unique with `floor` |
| `is_active` | boolean | Required, default `true` |
| `sensor_occupied` | boolean | Nullable; `NULL` means no sensor state is known |
| `sensor_updated_at` | datetime | Nullable |
| `last_detected_plate` | varchar(50) | Nullable |
| `last_plate_detected_at` | datetime | Nullable |

### `parking_records`

| Column | Type | Constraints / meaning |
|---|---|---|
| `parking_slot_id` | bigint unsigned | Required FK to `parking_slots`; restrict deletion |
| `vehicle_id` | bigint unsigned | Nullable FK to `vehicles`; cascade on vehicle deletion |
| `detected_plate` | varchar(50) | Nullable |
| `source` | varchar(20) | Required; default `manual` (sensor flows use `sensor`) |
| `parked_at` | datetime | Required |
| `released_at` | datetime | Nullable; null means current stay |

Indexes support the current-record queries on `(parking_slot_id, released_at)` and `(vehicle_id,
released_at)`. The schema does not declare a partial unique index for active stays; application transactions and
row locks enforce park/release/identify behavior.

### `users`

| Column | Type | Constraints / meaning |
|---|---|---|
| `name` | varchar(255) | Required |
| `email` | varchar(255) | Required, unique |
| `email_verified_at` | timestamp | Nullable |
| `password` | varchar(255) | Required; password hash, never plaintext |
| `role` | enum | `admin` or `staff`; default `staff` |
| `is_active` | boolean | Required, default `true` |
| `remember_token` | varchar(100) | Nullable |

`users` has no foreign keys into fleet records. Security events are file-based, not rows in this database.

## Laravel/framework tables

Laravel migrations also manage `password_reset_tokens`, `failed_jobs`, and `personal_access_tokens`.
`database/auth_tables.sql` documents optional `sessions`, `cache`, and `cache_locks` tables for deployments
using database-backed sessions/cache. Laravel creates and maintains the `migrations` table.

## Important data rules

- One active parking record is represented by `released_at IS NULL`; a sensor may create it without a
  `vehicle_id`.
- Slot `code` is unique per floor in the database. The sensor API looks up by code alone, so codes configured
  for sensor use must be unambiguous across floors.
- Vehicle/slot occupancy uniqueness is enforced in application transactions; do not bypass the app with direct
  writes that create conflicting active records.
- `security-YYYY-MM-DD.log` is the security-event store. The admin page reads a bounded, normalized view; logs
  are not persisted to a database table.

## Migration and import safety

Use `php artisan migrate` after making a backup. Do not import `database/vehicle_management.sql` into a live
database: it is a sample dump with `DROP TABLE` statements. The supplied attachment is a data-bearing legacy
dump, not a migration or a current VAPT report. `database/vehicle_management_schema.sql` is a schema-only
reference for review; for upgrades, Laravel migrations remain authoritative.
