# MISTER KOMPUTER — React + Supabase (Live Frontend)

**Current implementation:** [React app in `web/`](web/README.md) · [Vercel Production](https://mister-komputer-torray.vercel.app)

The PHP/MySQL and Docker code remains at repository root as the preserved original coursework. The current Vercel frontend deploys the **`web/` React+Vite** app and reads/writes Supabase `torray-workspace`.

**Data migration:** all six sample courses from the repository SQL were imported. Existing MySQL accounts, enrollments, certificates and payment history were **not** imported because no live MySQL export is available. Sign-in uses Supabase Auth, not MySQL password hashes. Payments are simulation requests only.

---

## Legacy PHP reference (not the deployed application)

# MISTER KOMPUTER — Course Management Prototype

A PHP + MySQL educational course-management system with course catalogues, enrollments, administrative roles, and manual payment review.

## What works in the current code

- Public course listing and detail pages
- Account registration and sign-in
- User/admin dashboards and course management
- Course enrollments and payment **request** recording
- Admin-only review of pending payments
- Responsive landing layout

## Security changes (rebuild pass)

- Registration stores a password using `password_hash()`; login checks with `password_verify()`.
- Removed seed accounts containing plaintext passwords.
- Database credentials must now come from environment variables.
- State-changing admin actions use POST + CSRF tokens instead of GET.
- Course editing now uses prepared statements.
- Payment submission **never automatically marks a payment verified**. Requests remain `pending` until manually reviewed.
- Session identifiers rotate after successful login.

**Important:** The old database credentials and sample passwords remain in Git history. Rotate any reused credentials. Existing plaintext-password accounts need a **proper password reset**; this update intentionally does not authenticate with old plaintext passwords.

## Development requirements

- PHP 8.1+ with mysqli and mbstring
- MySQL 8+
- Docker Engine + Compose (optional)

Create a local `.env` from `.env.example` with fresh private passwords, then:

```bash
docker compose up --build
```

Open `http://localhost:8080`. The database volume is persistent. To initialize a fresh database, import `database.sql` into the MySQL service. There are **no seeded login credentials** or automatic admin users; initialize an admin only through a trusted, operator-controlled process and store a password hash.

Do not use production student, payment, or customer details until this project completes authentication, authorization, backup, and privacy review.

## Architecture

```text
auth/          Login, registration and session
admin/         Course, user and payment management
pages/         Course catalog, enrollment, user dashboard
config/        Environment-based database connection
assets/css/    Frontend styling
database.sql   MySQL schema and demo course descriptions
```

## Hosting

This PHP/MySQL Docker app cannot be hosted as a simple Vercel static site. Use a PHP-capable host/container platform with MySQL and HTTPS. No public deployment is claimed.

## Roadmap

- [x] Remove plaintext-password login and SQL seed users
- [x] Environment-based database credentials
- [x] Manual payment-request workflow and CSRF on admin mutations
- [x] Responsive landing styling
- [ ] Role and authorization integration tests
- [ ] Password-reset flow and admin provisioning
- [ ] Payment verification evidence and audit trail
- [ ] Full database migration and deployment hardening
- [ ] Responsive E2E and accessibility checks

## Author
Tri Puji Antoro · 221011402646

**Status:** active rebuild, not yet production-ready.
