#!/bin/bash

# Final Database Import Script
# Run this AFTER: railway login

echo "🚂 Railway Database Import via Tunnel"
echo "======================================"
echo ""

# Check if logged in
if ! railway whoami &>/dev/null; then
    echo "❌ Not logged in. Please run: railway login"
    exit 1
fi

# Link to project if needed
if [ ! -f .railway/project.json ]; then
    echo "📎 Linking to Railway project..."
    railway link
    echo ""
fi

echo "🔌 Creating tunnel to MariaDB (this will run in background)..."
echo "   Tunnel will be available at: localhost:3307"
echo ""

# Start tunnel in background
railway connect mariadb --port 3307 &
TUNNEL_PID=$!

# Wait for tunnel to establish
echo "⏳ Waiting for tunnel to establish..."
sleep 8

echo "📥 Importing database (48MB file - this may take a few minutes)..."
echo ""

MYSQL_BIN="/opt/homebrew/opt/mysql-client/bin/mysql"
DB_FILE="../database/dbxgdxdldly759.sql"

$MYSQL_BIN -h 127.0.0.1 -P 3307 -u railway -p'ZPEMEUSSLV51Mqbf*zzsHVdwMCKk6p-n' railway < "$DB_FILE"

IMPORT_STATUS=$?

# Kill tunnel
echo ""
echo "🔌 Closing tunnel..."
kill $TUNNEL_PID 2>/dev/null
wait $TUNNEL_PID 2>/dev/null

if [ $IMPORT_STATUS -eq 0 ]; then
    echo ""
    echo "✅ Database imported successfully!"
    echo ""
    echo "Your WordPress database is now on Railway!"
    echo "Check your site at: https://primary-production-6a4bb.up.railway.app"
else
    echo ""
    echo "❌ Import failed. Error code: $IMPORT_STATUS"
    echo "Please check the error messages above."
    exit 1
fi

