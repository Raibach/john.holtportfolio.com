#!/bin/bash

# Import database via Railway tunnel
# This script creates a tunnel and imports through it

echo "🚂 Creating Railway tunnel to MariaDB..."
echo ""

# Create tunnel in background
railway connect mariadb --port 3307 &
TUNNEL_PID=$!

# Wait for tunnel to establish
sleep 5

echo "📥 Importing database through tunnel..."
/opt/homebrew/opt/mysql-client/bin/mysql -h 127.0.0.1 -P 3307 -u railway -p'ZPEMEUSSLV51Mqbf*zzsHVdwMCKk6p-n' railway < ../database/dbxgdxdldly759.sql

IMPORT_STATUS=$?

# Kill tunnel
kill $TUNNEL_PID 2>/dev/null

if [ $IMPORT_STATUS -eq 0 ]; then
    echo ""
    echo "✅ Database imported successfully!"
else
    echo ""
    echo "❌ Import failed. Please check the error above."
    exit 1
fi

