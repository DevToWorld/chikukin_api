#!/usr/bin/env bash
set -euo pipefail

# Usage:
#   export RAILWAY_TOKEN=...  # required
#   export RAILWAY_PROJECT_ID=...  # required once per link
#   export RAILWAY_ENV_ID=...      # optional (or set RAILWAY_ENV_NAME)
#   export RAILWAY_ENV_NAME=production  # optional alternative
#   export RAILWAY_SERVICE_ID=...  # optional (or select later)
#   ./scripts/railway-set-vars.sh
#
# This script logs in (token), links the project, selects env/service when provided,
# and sets CORS + mail variables for this app.

need() { if [ -z "${!1:-}" ]; then echo "Missing $1" >&2; exit 1; fi; }

need RAILWAY_TOKEN
need RAILWAY_PROJECT_ID

railway login --token "$RAILWAY_TOKEN" >/dev/null
railway link --project "$RAILWAY_PROJECT_ID" >/dev/null

if [ -n "${RAILWAY_ENV_ID:-}" ]; then
  railway environment --environmentId "$RAILWAY_ENV_ID" >/dev/null || true
elif [ -n "${RAILWAY_ENV_NAME:-}" ]; then
  railway environment "$RAILWAY_ENV_NAME" >/dev/null || true
fi

if [ -n "${RAILWAY_SERVICE_ID:-}" ]; then
  railway service --serviceId "$RAILWAY_SERVICE_ID" >/dev/null || true
fi

# CORS
railway variables set \
  CORS_ALLOWED_ORIGINS="https://modeitest.vercel.app" \
  CORS_ALLOWED_ORIGIN_PATTERNS="/^https:\\/\\/.*\\.vercel\\.app$/,/^https:\\/\\/.*\\.railway\\.app$/" \
  CORS_ALLOWED_HEADERS="Accept,Authorization,Content-Type,Origin,X-Requested-With" \
  CORS_EXPOSED_HEADERS="Authorization,X-RateLimit-Limit,X-RateLimit-Remaining,X-Request-Id" \
  CORS_SUPPORTS_CREDENTIALS="true"

# Mail (Resend) – uncomment to enable Resend path
# railway variables set \
#   MAIL_MAILER="resend" \
#   RESEND_API_KEY="<paste-your-resend-key>" \
#   MAIL_FROM_ADDRESS="betweentabin@gmail.com" \
#   MAIL_FROM_NAME="ちくぎん地域経済研究所" \
#   MAIL_DRY_RUN="false"

# Gmail API (optional) – DSN mode
# railway variables set \
#   MAIL_MAILER="gmail" \
#   MAIL_URL="gmail+api://betweentabin@gmail.com@default?client_id=<CLIENT_ID>&client_secret=<CLIENT_SECRET>&refresh_token=<REFRESH_TOKEN>" \
#   MAIL_FROM_ADDRESS="betweentabin@gmail.com" \
#   MAIL_FROM_NAME="ちくぎん地域経済研究所"

# Queue (optional, for bulk sending)
# railway variables set QUEUE_CONNECTION="database"

echo "Variables set. If you changed envs, redeploy or run: php artisan config:clear && php artisan route:clear && php artisan config:cache"
