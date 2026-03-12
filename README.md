# Beyond CRUD — Mental Health Care App

A domain-oriented Laravel application built following the architecture patterns from **"Laravel Beyond CRUD"** by Spatie. This project implements a mental health care system with two domains: **Inquiries** and **Patients**.

---

## Setup

### Requirements

- Docker & Docker Compose
- [Laravel Sail](https://laravel.com/docs/sail)

### Installation

```bash
# Clone the repository
git clone <repo-url> beyond-crud
cd beyond-crud

# Install PHP dependencies
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs

# Copy environment file and generate app key
cp .env.example .env
vendor/bin/sail up -d
vendor/bin/sail artisan key:generate

# Run migrations and seed test data
vendor/bin/sail artisan migrate --seed
```

### Running the Application

```bash
# Start all services
vendor/bin/sail up -d

# Stop all services
vendor/bin/sail stop
```

### Running Tests

```bash
# Run all tests
vendor/bin/sail artisan test --compact

# Run a specific test file
vendor/bin/sail artisan test tests/Domain/Inquiries/Actions/AdmitInquiryActionTest.php

# Filter by test name
vendor/bin/sail artisan test --filter=test_it_admits_a_pending_inquiry
```

### Code Style

```bash
# Fix formatting on changed files
vendor/bin/sail bin pint --dirty
```

---

## API Endpoints

All endpoints return JSON. Base URL: `http://localhost`.

### List Inquiries

```
GET /api/admin/inquiries
```

**Query parameters:**

| Parameter               | Type   | Description                        |
|-------------------------|--------|------------------------------------|
| `filter[state]`         | string | Exact state class name             |
| `filter[name]`          | string | Partial match on name              |
| `filter[created_after]` | date   | Inquiries created after this date  |
| `filter[created_before]`| date   | Inquiries created before this date |
| `sort`                  | string | `name`, `-name`, `created_at`, `-created_at` |

**Example:**

```bash
curl "http://localhost/api/admin/inquiries?filter[name]=Ana&sort=-created_at"
```

### Create Inquiry

```
POST /api/admin/inquiries
Content-Type: application/json
```

**Body:**

| Field          | Type   | Required | Rules              |
|----------------|--------|----------|--------------------|
| `name`         | string | yes      | max 255            |
| `email`        | string | yes      | valid email        |
| `phone`        | string | yes      | max 50             |
| `date_of_birth`| date   | yes      | must be before today |
| `reason`       | string | yes      |                    |
| `notes`        | string | no       |                    |

**Example:**

```bash
curl -X POST http://localhost/api/admin/inquiries \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Maria Silva",
    "email": "maria@example.com",
    "phone": "+55-11-99999-0000",
    "date_of_birth": "1990-05-15",
    "reason": "Anxiety management"
  }'
```

### Admit Inquiry

Transitions an inquiry from **Pending** to **Admitted** and creates a Patient record.

```
POST /api/admin/inquiries/{id}/admit
```

**Example:**

```bash
curl -X POST http://localhost/api/admin/inquiries/1/admit
```

### Patient Report

Returns a report of patients admitted within a date range.

```
GET /api/admin/patients
```

**Query parameters:**

| Parameter | Type | Default               |
|-----------|------|-----------------------|
| `start`   | date | Start of current year |
| `end`     | date | End of current year   |

**Example:**

```bash
curl "http://localhost/api/admin/patients?start=2025-01-01&end=2026-12-31"
```

### Console Command

```bash
vendor/bin/sail artisan patients:report --from=2025-01-01 --to=2026-12-31
```

---

## Architecture — Source Code Map

This project follows a **Domain-Oriented Laravel** architecture with three layers:

- **Domain** — Pure business logic, no framework dependencies.
- **Application** — Consumes the domain. Controllers, jobs, view models.
- **Support** — Generic reusable utilities (currently empty).

### Domain Models

Thin Eloquent models. Relations, casts, and overrides only — no business logic.

```
src/Domain/Inquiries/Models/Inquiry.php
src/Domain/Patients/Models/Patient.php
```

### States & Transitions

State pattern using `spatie/laravel-model-states`. Each state is a class with behavior methods (`colour()`, `canTransition()`). Transitions handle the state change and side effects.

```
src/Domain/Inquiries/States/
├── InquiryState.php                        # Abstract base, defines allowed transitions
├── PendingInquiryState.php                 # orange, can transition
├── AdmittedInquiryState.php                # green, terminal
├── RejectedInquiryState.php                # red, terminal
└── Transitions/
    ├── PendingToAdmittedTransition.php     # Changes state to Admitted
    └── PendingToRejectedTransition.php     # Changes state, saves rejection reason
```

### Actions

Single-responsibility classes with an `execute()` method. Actions compose other actions via constructor injection.

```
src/Domain/Inquiries/Actions/
├── CreateInquiryAction.php                 # Receives InquiryData DTO, creates Inquiry
├── AdmitInquiryAction.php                  # Runs transition, calls CreatePatientAction, dispatches event
└── RejectInquiryAction.php                 # Runs transition, saves rejection reason

src/Domain/Patients/Actions/
├── CreatePatientAction.php                 # Receives PatientData DTO, creates Patient
└── GeneratePatientReportAction.php         # Date range query, returns PatientReportData DTO
```

### Data Transfer Objects (DTOs)

Typed, readonly classes that structure data entering the domain. No logic inside.

```
src/Domain/Inquiries/DataTransferObjects/InquiryData.php
src/Domain/Patients/DataTransferObjects/PatientData.php
src/Domain/Patients/DataTransferObjects/PatientReportData.php
```

### Custom Query Builders

Replace Eloquent scopes. Each model overrides `newEloquentBuilder()` to return its custom builder.

```
src/Domain/Inquiries/QueryBuilders/InquiryQueryBuilder.php    # wherePending(), whereAdmitted(), whereCreatedBetween()
src/Domain/Patients/QueryBuilders/PatientQueryBuilder.php      # whereAdmittedBetween(), whereDischarged(), whereActive()
```

### Custom Collections

Replace inline collection chains. The Patient model overrides `newCollection()`.

```
src/Domain/Patients/Collections/PatientCollection.php          # activePatients(), dischargedPatients()
```

### Events & Listeners

Events are plain classes dispatched from actions. Subscribers group related listeners.

```
src/Domain/Inquiries/Events/InquiryAdmittedEvent.php          # Carries Inquiry + Patient
src/Domain/Inquiries/Listeners/InquirySubscriber.php           # Logs admission, registered in AppServiceProvider
```

### Exceptions

Domain-specific exceptions with static factory methods.

```
src/Domain/Inquiries/Exceptions/InvalidInquiryTransitionException.php
```

### Controllers

Thin controllers in the application layer. Receive request, build DTO, call action, return resource.

```
src/App/Admin/Inquiries/Controllers/
├── InquiriesController.php                # index (list with filters), store (create)
└── AdmitInquiryController.php             # __invoke (single-action controller)

src/App/Admin/Patients/Controllers/
└── PatientsController.php                 # index (report via ViewModel)
```

### Form Requests

Validation lives in dedicated request classes, not in controllers.

```
src/App/Admin/Inquiries/Requests/InquiryRequest.php
```

### API Resources

Map models to JSON responses.

```
src/App/Admin/Inquiries/Resources/InquiryResource.php
src/App/Admin/Patients/Resources/PatientResource.php
```

### ViewModels

Prepare data for views. Inject dependencies, call domain actions, expose computed properties.

```
src/App/Admin/Patients/ViewModels/PatientReportViewModel.php   # Implements Arrayable
```

### HTTP Query Builders

Extend `Spatie\QueryBuilder\QueryBuilder`. Define allowed filters, sorts, and defaults.

```
src/App/Admin/Inquiries/Queries/InquiryIndexQuery.php
```

### Jobs

Queue infrastructure only. The `handle()` method sends notifications — no business logic.

```
src/App/Admin/Inquiries/Jobs/SendAdmissionNotificationJob.php
```

### Console Commands

Same domain actions reused from the CLI.

```
src/App/Console/Commands/GeneratePatientReportCommand.php
```

### Test Factories

Custom immutable factories (not Laravel's default). Cloned on each state method call.

```
tests/Factories/InquiryFactory.php         # new()->create(), admitted(), rejected()
tests/Factories/PatientFactory.php         # new()->create(), discharged(), admittedAt(), forInquiry()
```

---

## Directory Structure

```
src/
├── Domain/                         # Business logic
│   ├── Inquiries/
│   │   ├── Actions/
│   │   ├── DataTransferObjects/
│   │   ├── Events/
│   │   ├── Exceptions/
│   │   ├── Listeners/
│   │   ├── Models/
│   │   ├── QueryBuilders/
│   │   └── States/
│   │       └── Transitions/
│   └── Patients/
│       ├── Actions/
│       ├── Collections/
│       ├── DataTransferObjects/
│       ├── Models/
│       └── QueryBuilders/
├── App/                            # Application layer
│   ├── Admin/
│   │   ├── Inquiries/
│   │   │   ├── Controllers/
│   │   │   ├── Jobs/
│   │   │   ├── Queries/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   └── Patients/
│   │       ├── Controllers/
│   │       ├── Resources/
│   │       └── ViewModels/
│   ├── Console/
│   │   └── Commands/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Providers/
└── Support/                        # Reusable utilities (empty)
```
