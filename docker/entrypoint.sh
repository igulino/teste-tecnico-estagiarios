#!/usr/bin/env sh
set -e

if [ -n "$DB_HOST" ]; then
    echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."

    until php -r "
        \$host = getenv('DB_HOST');
        \$port = getenv('DB_PORT') ?: 3306;
        \$socket = @fsockopen(\$host, (int) \$port);
        if (! \$socket) {
            exit(1);
        }
        fclose(\$socket);
    "; do
        sleep 2
    done
fi

php artisan migrate --force

exec "$@"
