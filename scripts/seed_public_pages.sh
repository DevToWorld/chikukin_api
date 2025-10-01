#!/usr/bin/env bash
set -euo pipefail

# Seed public pages to the specified PostgreSQL database.
# Usage:
#   ./scripts/seed_public_pages.sh <DATABASE_URL>
# or export DATABASE_URL and run without args.

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT_DIR"

DB_URL="${1:-${DATABASE_URL:-}}"
if [ -z "$DB_URL" ]; then
  echo "[ERROR] DATABASE_URL not provided.\nUsage: $0 <DATABASE_URL>" >&2
  exit 1
fi

echo "[INFO] Seeding public pages to: $DB_URL"

run_seed() {
  local seeder="$1"
  echo "\n[INFO] >>> Seeding: $seeder"
  DATABASE_URL="$DB_URL" php artisan db:seed --class="$seeder" --force
}

# Pages we manage via privacy-style or texts/htmls (non-destructive)
run_seed TermsPageJsonSeeder
run_seed TransactionLawPageJsonSeeder
run_seed CompanyProfilePageJsonSeeder
run_seed ConsultingPageJsonSeeder
run_seed AboutInstitutePageJsonSeeder
run_seed AboutPageJsonSeeder
run_seed MembershipPageJsonSeeder
run_seed FinancialReportsPageJsonSeeder
run_seed ContactPageJsonSeeder
run_seed SitemapPageJsonSeeder
run_seed GlossaryPageJsonSeeder
run_seed FaqPageJsonSeeder
run_seed HomePageJsonSeeder

# Images for per-page sections and Home hero slides/banners
run_seed PageNonKvImagesSeeder
run_seed HomePageSlidesSeeder

# Pre-create Block CMS (CMS v2) page entries so the list is populated
run_seed CmsV2DefaultPagesSeeder

echo "\n[INFO] All requested seeders executed successfully."
