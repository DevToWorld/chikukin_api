MySQL Utility Scripts

Overview
- Backup and restore a MySQL database using credentials from a Laravel `.env` file.
- Does not change application code; use when you need quick DB ops.

Usage
- Backup: `./scripts/mysql/backup.sh --env-file laravel-backend/.env.konoha.example`
- Restore: `./scripts/mysql/restore.sh path/to/dump.sql.gz --env-file laravel-backend/.env`

Notes
- Requires `mysqldump`, `mysql`, and `gzip` installed on the machine where you run the scripts.
- If your env file does not define MySQL, pass DB vars directly in the environment or via a custom env file.

