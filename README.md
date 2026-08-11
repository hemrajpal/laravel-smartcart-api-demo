# Laravel SmartCart API Demo

A simple **e-commerce REST API** built with **Laravel 12** for interview and demonstration purposes.

## Features

- User registration & login
- Laravel Sanctum authentication
- Email verification
- Welcome email
- Product CRUD
- Shopping cart
- Order & checkout
- Address management
- Payment integration
- Admin order management
- Role-based authorization
- Database notifications
- Email notifications
- Queue-based email/notification processing

## Tech Stack

- Laravel 12
- PHP
- MySQL / PostgreSQL
- Laravel Sanctum
- REST API
- Docker
- Render

## Setup

```bash
git clone <repository-url>
cd laravel-smartcart-api-demo

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate --seed

php artisan serve
```

API:

```text
http://127.0.0.1:8000/api
```

## Queue

For email and notification processing:

```bash
php artisan queue:work
```

## Demo Users

| Role | Email | Password |
|---|---|---|
| User | test@example.com | password |
| Admin | admin@example.com | password |

## Purpose

This project is created as an **interview/demo project** to demonstrate practical Laravel API development, authentication, database relationships, notifications, queues, and e-commerce functionality.
