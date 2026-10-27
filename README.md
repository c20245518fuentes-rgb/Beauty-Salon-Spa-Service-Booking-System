# Salon CRM Laravel app

This Laravel application implements a normalized salon scheduling and billing domain.

## Included domain model set

- Client
- Stylist
- Service
- Appointment
- BillingInvoice

## Core behavior

- 3NF normalized relational schema with primary and foreign key constraints
- Eloquent models with relationship methods
- Form requests for appointment and invoice validation
- Transactional service layer for sanitization and business logic
- Resource controllers for appointment and billing workflows
- Seed data for the provided salon records

## Run locally

```bash
composer install
php artisan migrate --seed
php artisan serve
```
