# EventHive Backend

A modern, secure event booking API built with **Laravel 12**, **PostgreSQL**, and **Sanctum**. This system supports role-based event management, attendee booking, and clean API documentation.

---

## Features

- Role-based access: `admin`, `organizer`, `attendee`
- Full CRUD for events (admin/organizer)
- Booking system with capacity + duplication prevention
- Token-based auth via Laravel Sanctum
- Tests for auth, bookings, edge cases (past, overbooked)
- Postman-ready API with dynamic token auth

---

## 📂 Folder Structure

```
eventhive-backend/
├── app/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
├── tests/
│   └── Feature/
│       ├── AuthTest.php
│       ├── BookingTest.php
│       └── EventTest.php
├── public/
├── resources/
├── postman-collection/
│   ├── EventHive_API_Collection.postman_collection
│   └── EventHive_Environment.postman_environment
└── README.md
```

---

## Setup Instructions

1. **Clone the repository**

```bash
git clone https://github.com/Mohd-Abdullah-AK/eventhive.git
cd eventhive-backend
```

2. **Install dependencies**

```bash
composer install
```

3. **Configure `.env`**

```bash
cp .env.example .env
php artisan key:generate
```

Update database settings for PostgreSQL:
```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=eventhive
DB_USERNAME=your_pg_user
DB_PASSWORD=your_pg_pass
```

4. **Run migrations and seeders**

```bash
php artisan migrate --seed
```

5. **Start the development server**

```bash
php artisan serve
```

---

## Database Schema Design

### Users
| Column         | Type       | Description                  |
|----------------|------------|------------------------------|
| id             | bigint     | Primary key                  |
| name           | string     | User name                    |
| email          | string     | Unique login email           |
| password       | string     | Hashed password              |
| role           | enum       | admin, organizer, attendee   |
| created_at     | timestamp  |                              |
| updated_at     | timestamp  |                              |

### Events
| Column         | Type       | Description                         |
|----------------|------------|-------------------------------------|
| id             | bigint     | Primary key                         |
| title          | string     | Event name                          |
| description    | text       | Optional description                |
| address        | string     | Venue address                       |
| city           | string     | City                                |
| country        | string     | Country                             |
| start_time     | datetime   | Event start                         |
| end_time       | datetime   | Event end                           |
| capacity       | integer    | Maximum number of attendees allowed |
| organizer_id   | foreign key| Linked to users table               |
| created_at     | timestamp  |                                     |
| updated_at     | timestamp  |                                     |

### Bookings
| Column         | Type       | Description                         |
|----------------|------------|-------------------------------------|
| id             | bigint     | Primary key                         |
| event_id       | foreign key| Linked to events table              |
| attendee_id    | foreign key| Linked to users table (attendee)    |
| created_at     | timestamp  |                                     |
| updated_at     | timestamp  |                                     |

- `bookings`: Unique constraint on (`event_id`, `attendee_id`)
- Capacity is enforced in code when booking
- Cannot book past events or double-book

---

## Sample Users (Seeded)

| Role      | Email                    | Password   |
|-----------|--------------------------|------------|
| Admin     | admin@eventhive.com      | password   |
| Organizer | organizer@eventhive.com  | password   |
| Attendee  | attendee@eventhive.com   | password   |

---


## Authentication

Use `/api/register` or `/api/login` to obtain a token.

Include it in headers for protected requests:
```
Authorization: Bearer YOUR_TOKEN
```

---

## API Documentation

Use Postman (recommended):

- `postman-collection/EventHive_API_Collection.postman_collection`
- `postman-collection/EventHive_Environment.postman_environment`

> Includes dynamic token injection. Use `register` or `login` to auto-fill token.

---

---

## Run Tests

```bash
php artisan test
```

Covers:
- Auth
- Booking (valid, duplicate, overbooked, past)
- Event CRUD

---