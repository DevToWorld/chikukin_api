MySQL Data Transfer Prep

Purpose
- Quickly carry over existing table data from your current DB (SQLite/Postgres/MySQL) to a new MySQL DB without touching existing features.

Flow
1) On the source environment (current DB):
   - Set `.env` to point to the source DB (`DB_CONNECTION=sqlite|pgsql|mysql`).
   - Run: `php artisan transfer:export`.
   - This creates NDJSON files under `storage/app/transfer/`.

2) Move the exported files to the target environment (MySQL):
   - Copy `storage/app/transfer/*.ndjson` to the server (same folder path if convenient).

3) On the target environment (MySQL):
   - Prepare schema with Laravel migrations: set `.env` for MySQL and run `php artisan migrate --force`.
   - Import data: `php artisan transfer:import --truncate`.

Options
- Export subset: `php artisan transfer:export --tables=members,publications`.
- Exclude some: `php artisan transfer:export --exclude=migrations`.
- Import subset: `php artisan transfer:import --tables=members,publications --truncate`.

Notes
- The importer filters columns to match the target table, so it tolerates schema differences.
- IDs are preserved if present in the dump.
- Foreign keys are temporarily disabled during import to avoid constraint issues.

