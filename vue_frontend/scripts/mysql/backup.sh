#!/usr/bin/env bash
set -euo pipefail

ENV_FILE=""
OUT_DIR="backups/mysql"

while [[ $# -gt 0 ]]; do
  case "$1" in
    --env-file)
      ENV_FILE="$2"; shift 2;;
    --out-dir)
      OUT_DIR="$2"; shift 2;;
    *) echo "Unknown arg: $1"; exit 1;;
  esac
done

if [[ -n "$ENV_FILE" ]]; then
  if [[ ! -f "$ENV_FILE" ]]; then
    echo "Env file not found: $ENV_FILE" >&2; exit 1
  fi
  export $(grep -E '^(DB_CONNECTION|DB_HOST|DB_PORT|DB_DATABASE|DB_USERNAME|DB_PASSWORD)=' "$ENV_FILE" | sed 's/#.*//')
fi

if [[ "${DB_CONNECTION:-}" != "mysql" ]]; then
  echo "DB_CONNECTION must be mysql (current: ${DB_CONNECTION:-unset}). Use --env-file to point to a MySQL .env." >&2
  exit 1
fi

mkdir -p "$OUT_DIR"
STAMP=$(date +%Y%m%d-%H%M%S)
OUT="$OUT_DIR/${DB_DATABASE}-${STAMP}.sql.gz"

echo "Backing up MySQL database '$DB_DATABASE' to $OUT"
mysqldump \
  -h "${DB_HOST}" -P "${DB_PORT:-3306}" \
  -u "${DB_USERNAME}" -p"${DB_PASSWORD}" \
  --single-transaction --routines --triggers --events --add-drop-table \
  "${DB_DATABASE}" | gzip -c > "$OUT"

echo "Done: $OUT"

