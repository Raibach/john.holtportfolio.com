FROM debian:bullseye-slim

# Install mysql client
RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL files and scripts from repository
COPY database/dbxgdxdldly759.sql /tmp/import.sql
COPY database-import/drop-tables.sh /drop-tables.sh
COPY database-import/drop-tables.sql /drop-tables.sql
RUN chmod +x /drop-tables.sh

# Create import script
RUN echo '#!/bin/bash\n\
set -e\n\
echo "🚀 Starting database import..."\n\
echo "Deployment triggered at $(date)"\n\
sleep 5\n\
\n\
IMPORT_SUCCESS=0\n\
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
  echo "Testing connection..."\n\
  mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" -e "SELECT 1" "$DB" || { echo "❌ Connection test failed!"; exit 1; }\n\
  echo "✅ Connection successful"\n\
  echo "Dropping existing tables first..."\n\
  /drop-tables.sh "$HOST" "$PORT" "$USER" "$PASS" "$DB" || echo "No tables to drop or drop failed (continuing...)"\n\
  echo "Importing database..."\n\
  if mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" < /tmp/import.sql; then\n\
    IMPORT_SUCCESS=1\n\
  else\n\
    echo "❌ Import failed with mysql command!"\n\
    IMPORT_SUCCESS=0\n\
  fi\n\
elif [ -n "$MYSQL_ROOT_PASSWORD" ]; then\n\
  echo "Using MYSQL_ROOT_PASSWORD..."\n\
  echo "Testing connection..."\n\
  mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1" railway || { echo "❌ Connection test failed!"; exit 1; }\n\
  echo "✅ Connection successful"\n\
  echo "Dropping existing tables first..."\n\
  /drop-tables.sh mysql.railway.internal 3306 root "$MYSQL_ROOT_PASSWORD" railway || echo "No tables to drop or drop failed (continuing...)"\n\
  echo "Importing database..."\n\
  if mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" railway < /tmp/import.sql; then\n\
    IMPORT_SUCCESS=1\n\
  else\n\
    echo "❌ Import failed with mysql command!"\n\
    IMPORT_SUCCESS=0\n\
  fi\n\
else\n\
  echo "❌ No MySQL credentials found!"\n\
  echo "Available environment variables:"\n\
  env | grep -i mysql || echo "No MYSQL_* variables found"\n\
  exit 1\n\
fi\n\
\n\
if [ $IMPORT_SUCCESS -eq 1 ]; then\n\
  echo "✅ Database imported successfully!"\n\
  echo "You can now delete this import service."\n\
else\n\
  echo "❌ Import failed!"\n\
  exit 1\n\
fi\n\
sleep 3600\n\
' > /import.sh && chmod +x /import.sh

CMD ["/import.sh"]

