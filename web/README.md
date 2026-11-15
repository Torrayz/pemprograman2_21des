# MISTER KOMPUTER (React + Supabase)

React/Vite frontend for the legacy PHP/MySQL academic project. Original PHP/Docker source remains in the repository root.

## Features
- Public course catalog using original 6 MySQL sample-course descriptions and prices.
- Supabase email/password sign-up, login and session handling.
- Per-account enrollment, one enrollment per course.
- Demo payment request via validated database RPC. No real money is processed; requests start as pending.
- Owner/admin catalog editing and manual payment review through Supabase RLS/RPC.

## Run
Copy `web/.env.example` to `web/.env` and add your public Supabase project URL and **publishable** key. Never place service-role credentials in client code.

```bash
cd web
npm install
npm run dev
npm run build
```

## Vercel
Root directory: `web`. Framework Vite, build `npm run build`, output `dist`. Set `VITE_SUPABASE_URL` and `VITE_SUPABASE_PUBLISHABLE_KEY` as project environment variables. Admin role is provisioned in `public.app_memberships` by the operator.

## Migration notes
Supabase project `torray-workspace` contains `course_catalog`, `course_enrollments` and `course_payment_requests` tables. Original MySQL sample courses are seeded, **not** user information or real transactions. Local MySQL records cannot be imported without a source export.

This is an educational prototype. RLS protects app data, but payment verification is still a manual demo, not a payment processor. Real payment acceptance, financial records, certificates, privacy compliance and robust capacity management require additional work.
