---
name: frontend-developer
description: Use for frontend tasks — Blade templates, Vue.js components, AdminLTE, Bootstrap, Laravel Mix/Webpack, CSS/SASS, and JS in this project.
---

You are working on the frontend of a Laravel 6 ERP called PRPOSYS.

## Stack

- **Build:** Laravel Mix 5 + Webpack (not Vite) — `webpack.mix.js` compiles to `public/js` and `public/css`
- **JS:** Vue.js 2.5, jQuery 3.2, Axios 0.19, Lodash 4.17, Chart.js 2.9
- **CSS:** Bootstrap 4, AdminLTE 3.0.5, Material Components Web 6, SASS/SCSS
- **Carousel:** Slick 1.8

Entry point: `resources/js/app.js` → `public/js/app.js`
Styles entry: `resources/sass/app.scss` → `public/css/app.css`

## Build Commands

```bash
npm run dev       # one-time dev build
npm run watch     # watch mode (preferred during development)
npm run prod      # minified production build
```

## View Structure

Each module has its own layout in `resources/views/layouts/`:
- `app.blade.php` — Sequence/Admin module
- `app-people.blade.php` — People module
- `app-leaves.blade.php` — Leaves module
- `app-travels.blade.php` — Travels module
- `app-resources.blade.php` — Resources module

Page views live in `resources/views/pages/{module}/`. Shared partials are in `resources/views/shared/`. Email templates are in `resources/views/mails/`.

## Conventions

- AdminLTE 3 card/box components are used throughout — follow existing patterns for new pages
- Modals are Bootstrap 4 modals, typically defined inline in the Blade file
- Vue components are used selectively for interactive widgets, not as a full SPA
- AJAX calls use Axios; form submissions mostly use standard Blade forms with CSRF
- No TypeScript — plain ES6 JS only

## Rules

- After any JS/CSS change, run `npm run dev` (or confirm watch is running) before testing in browser
- Use `@csrf` in all forms
- Do not introduce new npm packages without checking if the functionality already exists in the loaded libraries
- Always use Brave browser for dev server testing (`php artisan serve`)
