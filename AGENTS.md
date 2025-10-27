# Repository Guidelines

## Project Structure & Module Organization
This Laravel 12 service keeps domain code inside `app/`, with HTTP controllers and requests in `app/Http`, shared logic in `app/Services` and `app/Traits`, and notifications, charts, imports, and exports under their respective directories. Routes are defined in `routes/api.php` and `routes/web.php`, while Blade templates and Vite-driven assets live in `resources/` and compile to `public/`. Database migrations, seeders, and factories sit in `database/`, and automated coverage belongs in `tests/Feature` for end-to-end flows and `tests/Unit` for isolated logic.

## Build, Test, and Development Commands
Install dependencies via `composer install` and `npm install`. Use `composer run dev` for a synchronized developer stack (HTTP server, queue listener, live logs, Vite). Reach for `php artisan serve` when you only need the API, `npm run build` for production assets, and `composer test` or `php artisan test` to execute the PHPUnit suite.

## Coding Style & Naming Conventions
Target PHP 8.2, follow PSR-12, and keep four-space indentation. Run `vendor/bin/pint` before committing to auto-format PHP, and prefer framework generators (`php artisan make:*`) to enforce conventions. Name classes in StudlyCase (`CompanyController`, `AttendanceService`), keep Eloquent models singular, and use snake_case for database columns.

## Testing Guidelines
Extend `tests/TestCase.php` to leverage Laravel helpers, and isolate HTTP scenarios in `tests/Feature/*Test.php`. Unit-level specs belong in `tests/Unit/*Test.php`; seed data with factories and wrap DB-dependent tests in `RefreshDatabase`. Cover new services and controllers with both success and failure assertions, faking queues or notifications where applicable.

## Commit & Pull Request Guidelines
Write imperative, scope-prefixed commit titles such as `feat: add company import workflow` or `fix: guard empty chart dataset`, and keep each commit focused. Pull requests should summarize intent, link the ticket, flag schema or queue changes, and attach screenshots for UI updates. Always list the verification commands you ran (`php artisan test`, `npm run build`) and note any manual QA.

## Environment & Configuration
Start by copying `.env.example` to `.env`, configuring database, Sanctum, and FCM credentials, then run `php artisan key:generate` and `php artisan migrate`. Keep sensitive files (e.g., `fcm.json`) out of version control, relying on environment-specific secrets in deployment. Queue-powered features require a worker (`php artisan queue:work`), so mirror that locally when testing asynchronous flows.

