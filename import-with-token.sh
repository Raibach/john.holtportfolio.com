#!/bin/bash

# Import database using Railway API token
# Usage: RAILWAY_TOKEN=your_token ./import-with-token.sh

if [ -z "$RAILWAY_TOKEN" ]; then
    echo "❌ Please set RAILWAY_TOKEN environment variable"
    echo "   Example: export RAILWAY_TOKEN=your_token_here"
    echo "   Then run: ./import-with-token.sh"
    exit 1
fi

echo "🚂 Authenticating with Railway API token..."
export RAILWAY_TOKEN

# Try to use Railway CLI with token
railway whoami 2>&1

if [ $? -ne 0 ]; then
    echo "❌ Authentication failed. Please check your Railway token."
    exit 1
fi

echo "✅ Authenticated!"
echo ""

# Link to project if needed
if [ ! -f .railway/project.json ]; then
    echo "📎 Linking to Railway project..."
    railway link
    echo ""
fi

echo "🔌 Creating tunnel to MariaDB..."
railway connect mariadb --port 3307 &
TUNNEL_PID=$!

echo "⏳ Waiting for tunnel to establish..."
sleep 8

echo "📥 Importing database (48MB file - this may take a few minutes)..."
echo ""

MYSQL_BIN="/opt/homebrew/opt/mysql-client/bin/mysql"
DB_FILE="../database/dbxgdxdldly759.sql"

$MYSQL_BIN -h 127.0.0.1 -P 3307 -u railway -p'ZPEMEUSSLV51Mqbf*zzsHVdwMCKk6p-n' railway < "$DB_FILE"

IMPORT_STATUS=$?

echo ""
echo "🔌 Closing tunnel..."
kill $TUNNEL_PID 2>/dev/null

if [ $IMPORT_STATUS -eq 0 ]; then
    echo ""
    echo "✅ Database imported successfully!"
else
    echo ""
    echo "❌ Import failed. Error code: $IMPORT_STATUS"
    exit 1
fi

