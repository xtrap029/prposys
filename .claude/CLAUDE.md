# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Laravel
php artisan serve                    # start dev server
php artisan migrate
php artisan config:clear && php artisan cache:clear

## Architecture Overview

**Laravel 6** multi-module ERP with five distinct app modules, each with its own layout, middleware guards, and controller namespace:

| Module | Namespace | Layout |
|---|---|---|
| Sequence (Admin/Procurement) | `Admin\` | `layouts/app` |
| People (HR) | `People\` | `layouts/app-people` |
| Leaves | `Leaves\` | `layouts/app-leaves` |
| Travels | `Travels\` | `layouts/app-travels` |
| Resources | `Resources\` | `layouts/app-resources` |

All routes are in `routes/web.php` and require `auth` plus custom middleware (`CheckUserAccess`, `CheckConfidential`, `CheckReadOnly`). The API routes are minimal (`routes/api.php` has only one endpoint).

## Authorization System (UA)

User access is controlled via a custom UA (User Authorization) system — not Laravel Gates/Policies. Key models: `UaRoute`, `UaLevel`, `UaLevelRoute`. The `CheckUserAccess` middleware validates `ua_route` and `ua_level` against the user's role. Helpers in `app/Helpers/UAHelper.php`.

## Transaction Workflow

Core domain is procurement transactions with a multi-stage approval lifecycle defined in `config/global.php`:

`generated → for_approval → approved → issued → for_liquidation → liquidated → cleared`

Transaction categories: regular, deposit, bills, HR, reimbursement, fund transfer, TDSA, AEC, affiliate — each mapped to numeric IDs in `config/global.php`. The three largest controllers (`TransactionsController`, `TransactionsFormsController`, `TransactionsLiquidationController`) each exceed 60KB and contain the core workflow logic.

## Database-Driven Configuration

`AppServiceProvider` loads global site branding, module colors, banner images, file size limits, and external app links **from the database** at boot — not from config files. Changes to these settings require no code deploy but do require the DB to be seeded/migrated.

## Database Views

Leaves calculations rely on four MySQL views created by Artisan commands (listed above). These must be run after a fresh DB setup — they are not part of standard migrations.

## Frontend

Laravel Mix + Webpack (not Vite). Vue.js 2, Bootstrap 4, AdminLTE 3, jQuery. Entry: `resources/js/app.js` → `public/js`. No TypeScript.

## Mail

SMTP via Gmail app password (see `.env`). Mail classes are in `app/Mail/` — all notification types (approved, disapproved, for-approval, issued, almost-due, past-due). Queue is set to `sync`, so mail sends inline during the request.

## Activity Logging

Spatie `laravel-activitylog` is used on all critical models. The `LogsActivity` trait is present on most models. Logs are accessible via `People → Activity Logs` in the UI.

## Key Helpers

- `app/Helpers/TransactionHelper.php` — transaction business logic
- `app/Helpers/UAHelper.php` — user authorization checks
- `app/Helpers/UserHelper.php` — user utilities
- `app/Helpers/BreadHelper.php` — breadcrumb generation
```
