# Data Flow Diagrams

These diagrams describe the application flows implemented in the repository. They are documentation, not a
network deployment or VAPT assessment.

## DFD 0 — System context

```mermaid
flowchart LR
    Public[Public visitor]
    Staff[Authenticated staff]
    Admin[Administrator]
    Device[Arduino Uno + sensors + ESP-01]
    OCR[Camera / OCR service]
    Collector[Host SIEM collector]
    SIEM[External SIEM]

    App((Vehicle Management System))
    DB[(Configured SQL database)]
    Logs[(Daily JSON security logs)]

    Public -->|Landing page, robots.txt, sitemap.xml| App
    Staff -->|Login and fleet operations| App
    Admin -->|User administration and security-log view| App
    Device -->|Bearer-token occupancy events| App
    OCR -->|Separate bearer-token plate events| App
    App <-->|Validated domain reads and writes| DB
    App -->|Selected security events, JSON| Logs
    Admin -->|Read-only latest normalized events| Logs
    Logs -->|Host-configured collection; not sent by app| Collector
    Collector -->|Forwarding configured outside this repository| SIEM
```

## DFD 1 — Browser request and application processing

```mermaid
flowchart LR
    Browser[Staff / admin browser]
    Router[Laravel web routes]
    Middleware[Session auth, active-user check, CSRF, admin check]
    UI[Blade + Livewire / PowerGrid]
    Controllers[Controllers, validation, business rules]
    Models[Eloquent models]
    DB[(SQL database)]
    SecurityLogger[SecurityEventLogger]
    LogFile[(storage/logs/security-YYYY-MM-DD.log)]
    LogReader[Admin SecurityLogReader]

    Browser -->|HTTPS request + session cookie| Router
    Router --> Middleware
    Middleware -->|Allowed web request| UI
    UI --> Controllers
    Controllers --> Models
    Models <-->|SQL| DB
    Controllers -->|Authentication, profile, account, authorization events| SecurityLogger
    Middleware -->|Denied-access event| SecurityLogger
    SecurityLogger -->|JSON, allowlisted context| LogFile
    Browser -->|Admin-only /security-logs| Router
    Router -->|Admin middleware passes| LogReader
    LogReader -->|Bounded tail read, selected fields only| LogFile
    LogReader -->|Escaped HTML table| Browser
```

## DFD 1 — Parking sensor and OCR event processing

```mermaid
sequenceDiagram
    participant S as Parking sensor gateway
    participant T as Token middleware
    participant C as ParkingSensorController
    participant DB as SQL database
    participant O as OCR/camera service
    participant UI as Authenticated parking map

    S->>T: POST /api/parking/sensor (sensor bearer token, slot_code, occupied)
    T->>T: Check separate token, ten-slot configuration, and slot configuration
    T->>C: Validated request
    C->>DB: Transaction + lock slot/current record
    C->>DB: Update sensor state; create/close parking record
    C-->>S: JSON status

    O->>T: POST /api/parking/plate (OCR bearer token, slot_code, plate)
    T->>C: Validated request
    C->>DB: Transaction + lock slot/current record
    C->>DB: Save normalized plate; link eligible vehicle or leave unidentified
    C-->>O: JSON status

    UI->>DB: Read current slot and parking-record state
    DB-->>UI: Occupancy, detected plate, optional vehicle
```

## Trust boundaries and operational notes

- The browser-facing application should be deployed behind HTTPS. Laravel session/CSRF protection applies to
  web forms; route middleware protects authenticated and admin-only screens.
- Sensor and OCR calls use distinct bearer tokens. The sample ESP-01 firmware uses plain HTTP and is intended
  only for a trusted private LAN; do not expose it to the public internet.
- The host collector and SIEM are outside the Laravel application boundary. Configure file permissions,
  collection, transport security, alerting, and retention in the deployment environment.
- The security log page is read-only and does not turn the app into a SIEM. It displays at most 200 normalized
  recent events from a bounded log-file read.
- Public SEO routes do not access private fleet data. `robots.txt` and `noindex` are not authorization controls.
