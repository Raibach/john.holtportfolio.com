FROM mysql:8.0

# Install mysql client
RUN apt-get update && apt-get install -y mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL file from repository
COPY database/dbxgdxdldly759.sql /tmp/import.sql

# Create import script
RUN echo '#!/bin/bash\n\
echo "🚀 Starting database import..."\n\
echo "Connecting to MySQL at mysql.railway.internal:3306"\n\
sleep 5\n\
mysql -h mysql.railway.internal \\\n\
      -P 3306 \\\n\
      -u root \\\n\
      -p"${MYSQL_ROOT_PASSWORD}" \\\n\
      railway < /tmp/import.sql\n\
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

