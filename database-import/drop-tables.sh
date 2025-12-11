#!/bin/bash
# Drop all tables in the specified database
# Usage: drop-tables.sh HOST PORT USER PASSWORD DATABASE

HOST="$1"
PORT="$2"
USER="$3"
PASS="$4"
DB="$5"

if [ -z "$HOST" ] || [ -z "$PORT" ] || [ -z "$USER" ] || [ -z "$PASS" ] || [ -z "$DB" ]; then
    echo "Usage: drop-tables.sh HOST PORT USER PASSWORD DATABASE"
    exit 1
fi

# Execute the drop-tables SQL script
mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" < /drop-tables.sql 2>&1
