#!/usr/bin/env bash
set -euo pipefail

if [[ $# -lt 1 ]]; then
  echo "Usage: $0 <dump.sql|dump.sql.gz> [--env-file path]" >&2
  exit 1
fi

DUMP="$1"; shift
ENV_FILE=""

while [[ $# -gt 0 ]]; do
  case "$1" in
    --env-file)
      ENV_FILE="$2"; shift 2;;
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

if [[ ! -f "$DUMP" ]]; then
  echo "Dump file not found: $DUMP" >&2
  exit 1
fi

echo "Restoring into '$DB_DATABASE' from $DUMP"
set +e
if [[ "$DUMP" == *.gz ]]; then
  gunzip -c "$DUMP" | mysql -h "${DB_HOST}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}"
else
  mysql -h "${DB_HOST}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" < "$DUMP"
fi
RC=$?
set -e

if [[ $RC -ne 0 ]]; then
  echo "Restore failed (exit $RC)" >&2
  exit $RC
fi

echo "Restore completed."

