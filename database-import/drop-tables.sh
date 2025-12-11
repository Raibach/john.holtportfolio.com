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

echo "Dropping all tables in database: $DB"

# Use a more reliable method to drop all tables
mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" <<EOF 2>&1
SET FOREIGN_KEY_CHECKS=0;
SET @tables = NULL;
SELECT GROUP_CONCAT(CONCAT('DROP TABLE IF EXISTS \`', table_name, '\`')) INTO @tables
FROM information_schema.tables
WHERE table_schema = '$DB';
SET @tables = IFNULL(@tables, '');
SET @tables = IF(@tables != '', @tables, 'SELECT 1');
PREPARE stmt FROM @tables;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET FOREIGN_KEY_CHECKS=1;
EOF

exit $?
