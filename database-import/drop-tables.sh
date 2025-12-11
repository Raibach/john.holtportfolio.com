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

# Method 1: Try using GROUP_CONCAT with proper separator
DROP_RESULT=$(mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" <<'EOF' 2>&1
SET FOREIGN_KEY_CHECKS=0;
SET @tables = NULL;
SELECT GROUP_CONCAT(CONCAT('DROP TABLE IF EXISTS `', table_name, '`') SEPARATOR ';') INTO @tables
FROM information_schema.tables
WHERE table_schema = DATABASE();
SET @tables = IFNULL(@tables, '');
SET @tables = IF(@tables != '', CONCAT(@tables, ';'), 'SELECT 1');
PREPARE stmt FROM @tables;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
SET FOREIGN_KEY_CHECKS=1;
SELECT 1;
EOF
)

# If that failed, try dropping tables one by one
if [ $? -ne 0 ] || echo "$DROP_RESULT" | grep -q "ERROR"; then
    echo "Batch drop failed, trying individual table drops..."
    mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" -e "SET FOREIGN_KEY_CHECKS=0;" 2>&1
    for table in $(mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" -N -e "SELECT table_name FROM information_schema.tables WHERE table_schema = '$DB';" 2>/dev/null); do
        echo "Dropping table: $table"
        mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" -e "DROP TABLE IF EXISTS \`$table\`;" 2>&1
    done
    mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" -e "SET FOREIGN_KEY_CHECKS=1;" 2>&1
    echo "Individual drop completed"
else
    echo "Batch drop successful"
fi

exit 0
