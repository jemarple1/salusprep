#!/usr/bin/env bash
set -euo pipefail

php artisan config:clear

# Skip migrations until Laravel Cloud injects a real database host.
# Defaults (127.0.0.1 / laravel) mean no database is attached yet.
if [[ -n "${DB_HOST:-}" && "${DB_HOST}" != "127.0.0.1" && -n "${DB_DATABASE:-}" && "${DB_DATABASE}" != "laravel" ]]; then
    echo "Database detected (${DB_HOST}) — running migrations..."
    php artisan migrate --force

    if [[ "${RUN_DB_SEED:-false}" == "true" ]]; then
        php artisan db:seed --force
    fi

    php artisan optimize
else
    echo "No Cloud database attached yet (DB_HOST=${DB_HOST:-unset})."
    echo "Skipping migrate. Attach MySQL on the infrastructure canvas, then redeploy."
fi
