FROM debian:bullseye-slim

# Install mysql client
RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL file
COPY database/dbxgdxdldly759.sql /tmp/import.sql

# Create import script
RUN echo '#!/bin/bash\n\
set -e\n\
echo "🚀 Starting database import..."\n\
echo "Available env vars:"\n\
env | grep -i mysql || echo "No MYSQL vars"\n\
sleep 5\n\
\n\
IMPORT_SUCCESS=0\n\
\n\
if [ -n "$MYSQL_PRIVATE_URL" ]; then\n\
  MYSQL_URL="${MYSQL_PRIVATE_URL#mysql://}"\n\
  USER_PASS="${MYSQL_URL%%@*}"\n\
  HOST_DB="${MYSQL_URL#*@}"\n\
  USER="${USER_PASS%%:*}"\n\
  PASS="${USER_PASS#*:}"\n\
  HOST="${HOST_DB%%:*}"\n\
  PORT_DB="${HOST_DB#*:}"\n\
  PORT="${PORT_DB%%/*}"\n\
  DB="${PORT_DB#*/}"\n\
  echo "Connecting to $HOST:$PORT/$DB as $USER"\n\
  echo "Testing connection..."\n\
  mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" -e "SELECT 1" "$DB" || { echo "❌ Connection failed!"; exit 1; }\n\
  echo "✅ Connection successful"\n\
  echo "Dropping existing tables..."\n\
  mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" -e "SET FOREIGN_KEY_CHECKS=0; SET @tables = NULL; SELECT GROUP_CONCAT(CONCAT('\''DROP TABLE IF EXISTS `'\'', table_name, '\''`'\'') SEPARATOR '\'';'\'') INTO @tables FROM information_schema.tables WHERE table_schema = '\''$DB'\''; SET @tables = IFNULL(@tables, '\''SELECT 1'\''); SET @tables = IF(@tables != '\'''\'', CONCAT(@tables, '\'';'\''), '\''SELECT 1'\''); PREPARE stmt FROM @tables; EXECUTE stmt; DEALLOCATE PREPARE stmt; SET FOREIGN_KEY_CHECKS=1;" 2>&1 || echo "No tables to drop"\n\
  echo "Importing database..."\n\
  if mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" < /tmp/import.sql 2>&1; then\n\
    IMPORT_SUCCESS=1\n\
  else\n\
    echo "❌ Import failed!"\n\
    IMPORT_SUCCESS=0\n\
  fi\n\
elif [ -n "$MYSQL_ROOT_PASSWORD" ]; then\n\
  echo "Connecting to mysql.railway.internal:3306/railway as root"\n\
  echo "Testing connection..."\n\
  mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" railway || { echo "❌ Connection failed!"; exit 1; }\n\
  echo "✅ Connection successful"\n\
  echo "Dropping existing tables..."\n\
  mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" railway -e "SET FOREIGN_KEY_CHECKS=0; SET @tables = NULL; SELECT GROUP_CONCAT(CONCAT('\''DROP TABLE IF EXISTS `'\'', table_name, '\''`'\'') SEPARATOR '\'';'\'') INTO @tables FROM information_schema.tables WHERE table_schema = '\''railway'\''; SET @tables = IFNULL(@tables, '\''SELECT 1'\''); SET @tables = IF(@tables != '\'''\'', CONCAT(@tables, '\'';'\''), '\''SELECT 1'\''); PREPARE stmt FROM @tables; EXECUTE stmt; DEALLOCATE PREPARE stmt; SET FOREIGN_KEY_CHECKS=1;" 2>&1 || echo "No tables to drop"\n\
  echo "Importing database..."\n\
  if mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" railway < /tmp/import.sql 2>&1; then\n\
    IMPORT_SUCCESS=1\n\
  else\n\
    echo "❌ Import failed!"\n\
    IMPORT_SUCCESS=0\n\
  fi\n\
else\n\
  echo "❌ No MySQL credentials found!"\n\
  exit 1\n\
fi\n\
\n\
if [ $IMPORT_SUCCESS -eq 1 ]; then\n\
  echo "✅ Database imported successfully!"\n\
else\n\
  echo "❌ Import failed!"\n\
  exit 1\n\
fi\n\
sleep 3600\n\
' > /import.sh && chmod +x /import.sh

CMD ["/import.sh"]
