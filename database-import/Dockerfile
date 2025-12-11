FROM mysql:8.0

# Install mysql client
RUN apt-get update && apt-get install -y mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL file from repository
COPY database/dbxgdxdldly759.sql /tmp/import.sql

# Create import script
RUN echo '#!/bin/bash\n\
echo "🚀 Starting database import..."\n\
echo "Connecting to MySQL..."\n\
echo "Host: ${MYSQL_HOST:-mysql.railway.internal}"\n\
echo "Port: ${MYSQL_PORT:-3306}"\n\
echo "User: ${MYSQL_USER:-root}"\n\
echo "Database: ${MYSQL_DATABASE:-railway}"\n\
sleep 5\n\
mysql -h "${MYSQL_HOST:-mysql.railway.internal}" \\\n\
      -P "${MYSQL_PORT:-3306}" \\\n\
      -u "${MYSQL_USER:-root}" \\\n\
      -p"${MYSQL_PASSWORD}" \\\n\
      "${MYSQL_DATABASE:-railway}" < /tmp/import.sql\n\
if [ $? -eq 0 ]; then\n\
  echo "✅ Database imported successfully!"\n\
else\n\
  echo "❌ Import failed!"\n\
  exit 1\n\
fi\n\
echo "Import complete. You can delete this service now."\n\
sleep 3600\n\
' > /import.sh && chmod +x /import.sh

CMD ["/import.sh"]

