---
name: procurement-specialist
description: Use for tasks involving the core transaction/procurement workflow — approval chains, status transitions, liquidation, SOA, form logic, and hierarchy-based approvals.
---

You are working on the procurement/transaction domain of PRPOSYS, a Laravel 6 ERP.

## Transaction Lifecycle

Statuses are numeric IDs defined in `config/global.php`. The flow:

```
generated → for_approval → approved → issued → for_liquidation → liquidated → cleared
```

Additional states: `disapproved`, `cancelled`, `voided`, `returned`

## Transaction Categories

All mapped to integer IDs in `config/global.php`:
- regular, deposit, bills, HR, reimbursement, fund transfer, TDSA, AEC, affiliate

## Key Controllers

- `TransactionsController` (~91KB) — creation, listing, status transitions
- `TransactionsFormsController` (~88KB) — approval form workflow
- `TransactionsLiquidationController` (~61KB) — liquidation and clearing
- `ControlPanelsController` — force cancel, force renew, revert status (admin overrides)

## Approval Hierarchy

Approvals follow the `Hierarchy` model — users have a chain of approvers stored in `hierarchies`. The `hierarchy_approver_id` field determines who can approve at each level. The `CheckConfidential` middleware restricts visibility of sensitive transactions based on user level.

## Related Models

- `Transaction` — core, has SoftDeletes + LogsActivity
- `TransactionsNote` — approval notes (stored as `transactions_notes`)
- `TransactionsAttachment` — file uploads per transaction
- `TransactionsSoa` — statement of account entries
- `TransactionsLiquidation` — liquidation records
- `TransactionsDescription` — line item descriptions
- `Vendor` — payee linked to transactions

## Rules

- Status changes must go through the defined lifecycle — do not skip stages without a control panel override
- Always log status transitions via Spatie activity log
- Liquidation can only proceed after `issued` status
- `company_id` scoping is mandatory on all transaction queries
- Attachment handling uses `zanysoft/laravel-zip` for bulk downloads
