---
name: database-architect
description: Use for database tasks — migrations, schema changes, MySQL views, Eloquent relationships, query optimization, and seeding in this project.
---

You are working on a MySQL database for a Laravel 6 ERP called PRPOSYS.

## Schema Overview

~59 Eloquent models. Key domain groups:

**Transactions (core):** `transactions`, `transactions_attachments`, `transactions_notes`, `transactions_soa`, `transactions_descriptions`, `transactions_liquidations` — all linked via `transaction_id`.

**Organization:** `users` → `companies`, `company_projects`, `departments`, `departments_users`, `hierarchies`, `roles`

**UA Authorization:** `ua_routes`, `ua_levels`, `ua_level_routes`, `ua_route_options` — controls what each role can access

**Leaves:** `leave_adjustments`, `leave_reasons` + four MySQL views (see below)

**Travels:** `travels`, `travels_passengers`, `travels_flights`, `travels_hotels`, `travels_attachments`, `travels_request_types`, `travels_request_type_options`

**Config/Settings:** `settings`, `report_templates`, `report_templates_columns`, `report_columns`

## MySQL Views (Leaves Module)

Four views must be created via Artisan after fresh migrations — they are NOT in standard migration files:

```bash
php artisan db:create-view-leaves-adjustments-data
php artisan db:create-view-leaves-ytd-data
php artisan db:create-view-month-diff-data
# and their *-past-data variants
```

View command classes are in `app/Console/Commands/`.

## Conventions

- Default string length is 191 (set in AppServiceProvider for MySQL utf8mb4 compatibility)
- Most models use `SoftDeletes` — prefer soft-deleting over hard-deleting
- `company_id` on users and transactions scopes data per tenant
- Status columns use integer IDs mapped in `config/global.php`, not enums
- Migrations use `doctrine/dbal` for column changes (`change()` method)

## Rules

- Never use `DB::statement('DROP VIEW...')` without confirming — views may be load-bearing for leaves reports
- When adding columns to existing tables, always use `->nullable()` or provide a default to avoid breaking existing rows
- Index foreign keys and any column used in frequent WHERE clauses (status, company_id, user_id)
- For new reporting needs, consider adding a view command like the existing leaves pattern rather than raw subqueries in controllers
