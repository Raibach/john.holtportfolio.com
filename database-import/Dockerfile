FROM debian:bullseye-slim

# Install mysql client
RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL file
COPY database/dbxgdxdldly759.sql /tmp/import.sql

# Create import script
RUN echo '#!/bin/bash\n\
echo "🚀 Starting database import..."\n\
sleep 5\n\
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
  echo "Importing to $DB..."\n\
  mysql -h "$HOST" -P "$PORT" -u "$USER" -p"$PASS" "$DB" < /tmp/import.sql\n\
elif [ -n "$MYSQL_ROOT_PASSWORD" ]; then\n\
  echo "Importing to railway database..."\n\
  mysql -h mysql.railway.internal -P 3306 -u root -p"$MYSQL_ROOT_PASSWORD" railway < /tmp/import.sql\n\
else\n\
  echo "❌ No MySQL credentials found!"\n\
  exit 1\n\
fi\n\
\n\
if [ $? -eq 0 ]; then\n\
  echo "✅ Database imported successfully!"\n\
else\n\
  echo "❌ Import failed!"\n\
  exit 1\n\
fi\n\
sleep 3600\n\
' > /import.sh && chmod +x /import.sh

CMD ["/import.sh"]
