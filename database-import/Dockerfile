FROM debian:bullseye-slim

# Install mysql client
RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL file from repository
COPY database/dbxgdxdldly759.sql /tmp/import.sql

# Create import script
RUN echo '#!/bin/bash\n\
echo "🚀 Starting database import..."\n\
sleep 5\n\
\n\
# Try using MYSQL_PRIVATE_URL if available\n\
if [ -n "$MYSQL_PRIVATE_URL" ]; then\n\
  echo "Using MYSQL_PRIVATE_URL connection..."\n\
  # Extract components from URL\n\
  MYSQL_URL="${MYSQL_PRIVATE_URL#mysql://}"\n\
  USER_PASS="${MYSQL_URL%%@*}"\n\
  HOST_DB="${MYSQL_URL#*@}"\n\
  USER="${USER_PASS%%:*}"\n\
  PASS="${USER_PASS#*:}"\n\
  HOST="${HOST_DB%%:*}"\n\
  PORT_DB="${HOST_DB#*:}"\n\
  PORT="${PORT_DB%%/*}"\n\
  DB="${PORT_DB#*/}"\n\
  echo "Connecting as $USER to $HOST:$PORT/$DB"\n\
  echo "Dropping existing tables first..."\n\
  mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" -e "SET FOREIGN_KEY_CHECKS=0; SET @tables = NULL; SELECT GROUP_CONCAT(table_name) INTO @tables FROM information_schema.tables WHERE table_schema = '$DB'; SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables); PREPARE stmt FROM @tables; EXECUTE stmt; DEALLOCATE PREPARE stmt; SET FOREIGN_KEY_CHECKS=1;" 2>&1 || echo "No tables to drop or error dropping"\n\
  echo "Importing database..."\n\
  mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" < /tmp/import.sql\n\
elif [ -n "$MYSQL_ROOT_PASSWORD" ]; then\n\
  echo "Using MYSQL_ROOT_PASSWORD..."\n\
  echo "Dropping existing tables first..."\n\
  mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" railway -e "SET FOREIGN_KEY_CHECKS=0; SET @tables = NULL; SELECT GROUP_CONCAT(table_name) INTO @tables FROM information_schema.tables WHERE table_schema = 'railway'; SET @tables = CONCAT('DROP TABLE IF EXISTS ', @tables); PREPARE stmt FROM @tables; EXECUTE stmt; DEALLOCATE PREPARE stmt; SET FOREIGN_KEY_CHECKS=1;" 2>&1 || echo "No tables to drop"\n\
  echo "Importing database..."\n\
  mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" railway < /tmp/import.sql\n\
else\n\
  echo "No MySQL credentials found!"\n\
  exit 1\n\
fi\n\
\n\
if [ $? -eq 0 ]; then\n\
  echo "✅ Database imported successfully!"\n\
  echo "You can now delete this import service."\n\
else\n\
  echo "❌ Import failed!"\n\
  exit 1\n\
fi\n\
sleep 3600\n\
' > /import.sh && chmod +x /import.sh

CMD ["/import.sh"]

