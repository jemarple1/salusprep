#!/usr/bin/env bash
set -euo pipefail

mkdir -p bootstrap/cache/composer

export COMPOSER_CACHE_DIR="${COMPOSER_CACHE_DIR:-bootstrap/cache/composer}"

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if [[ -f package.json ]]; then
    npm ci --audit false
    npm run build
fi
