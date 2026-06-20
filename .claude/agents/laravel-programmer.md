---
name: laravel-programmer
description: Use for Laravel backend tasks — controllers, models, middleware, mail, routes, service providers, Eloquent queries, and business logic in this project.
---

You are working on a Laravel 6 multi-module ERP called Sequence/PRPOSYS.

## Project Context

- Five modules: Sequence (Admin/Procurement), People (HR), Leaves, Travels, Resources
- Each module has its own controller namespace under `app/Http/Controllers/{Module}/`
- Routes are all in `routes/web.php`, grouped by module with auth + UA middleware
- Custom authorization via UA system (not Laravel Gates) — see `app/Helpers/UAHelper.php`
- Activity logging via Spatie `laravel-activitylog` on all critical models
- Queue is `sync` — no background worker; mail and jobs run inline

## Transaction Workflow

Core procurement lifecycle (statuses defined in `config/global.php`):
`generated → for_approval → approved → issued → for_liquidation → liquidated → cleared`

Categories: regular, deposit, bills, HR, reimbursement, fund transfer, TDSA, AEC, affiliate — all mapped to numeric IDs in `config/global.php`.

Heaviest controllers:
- `app/Http/Controllers/Admin/TransactionsController.php` (~91KB)
- `app/Http/Controllers/Admin/TransactionsFormsController.php` (~88KB)
- `app/Http/Controllers/Admin/TransactionsLiquidationController.php` (~61KB)

## Conventions

- Models use SoftDeletes and LogsActivity traits where applicable
- AppServiceProvider loads branding/config from DB at boot — no static config for those values
- Mail classes live in `app/Mail/` — one class per notification type
- Helpers in `app/Helpers/` handle breadcrumbs, UA checks, user utilities, transaction logic
- Views use Blade with AdminLTE 3 components; shared partials in `resources/views/shared/`

## Rules

- Always scope queries to the user's company where relevant (`company_id`)
- When adding new routes, follow the middleware pattern in existing route groups
- When adding mail, create a class in `app/Mail/` and a Blade template in `resources/views/mails/`
- Do not switch queue driver to async without confirming with the user — sync is intentional
