# Student Learning Hub (Bobon B ES)

Hi! This is a teacher-only, desktop-first web app for Bobon B Elementary School. The goal is simple: keep lessons, MATATAG competencies, assessments, and reports in one place, and make it usable even with slow internet (offline features are part of the plan).

## Stack
- Backend: PHP 8.2 + Laravel 11
- Frontend: Blade templates + minimal JavaScript
- Database: MySQL or MariaDB

## Current Status
- Admin and teacher accounts
- School years, grades, sections, students, enrollments
- Subjects and MATATAG competencies (curriculum mapping)
- Lessons and resource library (uploads)
- Assessments, items, and scoring

## Setup
### Requirements
- PHP 8.2
- Composer
- Node.js + npm
- MySQL or MariaDB

### Installation
```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials, then run:
```bash
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
php artisan serve
```

Visit `http://127.0.0.1:8000`.

## Seeded Accounts
- Admin: `admin@bobonb.local` / `password`
- Teacher: `teacher@bobonb.local` / `password`

## Common Commands
```bash
vendor\bin\pint
php artisan test
```

## Notes
- Public registration is disabled. Admins create teacher accounts.
- Internal docs are not included in the public repo.
