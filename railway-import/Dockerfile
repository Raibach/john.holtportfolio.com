FROM mysql:8.0

# Install mysql client
RUN apt-get update && apt-get install -y mysql-client && rm -rf /var/lib/apt/lists/*

# Copy SQL file
COPY dbxgdxdldly759.sql /tmp/import.sql

# Create import script
RUN echo '#!/bin/bash\n\
echo "Waiting for MariaDB to be ready..."\n\
sleep 10\n\
echo "Importing database..."\n\
mysql -h mariadb.railway.internal -P 3306 -u railway -p"$MARIADB_PASSWORD" railway < /tmp/import.sql\n\
echo "Import complete!"\n\
sleep 3600\n\
' > /import.sh && chmod +x /import.sh

CMD ["/import.sh"]

