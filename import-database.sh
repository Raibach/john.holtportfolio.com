#!/bin/bash

# Railway Database Import Script
# Run this AFTER: railway login

echo "🚂 Railway Database Import Script"
echo "================================"
echo ""

# Check if Railway CLI is logged in
if ! railway whoami &>/dev/null; then
    echo "❌ Not logged in to Railway. Please run: railway login"
    exit 1
fi

echo "✅ Logged in to Railway"
echo ""

# Link to project if not already linked
if [ ! -f .railway/project.json ]; then
    echo "📎 Linking to Railway project..."
    railway link
    echo ""
fi

# Get MySQL connection variables from Railway
echo "🔍 Getting MySQL connection details from Railway..."
MYSQL_HOST=$(railway variables --json | grep -o '"MYSQLHOST":"[^"]*' | cut -d'"' -f4)
MYSQL_PORT=$(railway variables --json | grep -o '"MYSQLPORT":"[^"]*' | cut -d'"' -f4)
MYSQL_USER=$(railway variables --json | grep -o '"MYSQLUSER":"[^"]*' | cut -d'"' -f4)
MYSQL_PASSWORD=$(railway variables --json | grep -o '"MYSQLPASSWORD":"[^"]*' | cut -d'"' -f4)
MYSQL_DATABASE=$(railway variables --json | grep -o '"MYSQLDATABASE":"[^"]*' | cut -d'"' -f4)

if [ -z "$MYSQL_HOST" ]; then
    echo "❌ Could not get MySQL connection details. Trying alternative method..."
    railway variables | grep MYSQL
    echo ""
    echo "Please run manually:"
    echo "mysql -h [HOST] -P [PORT] -u [USER] -p[PASSWORD] [DATABASE] < ../database/dbxgdxdldly759.sql"
    exit 1
fi

echo "✅ Found MySQL connection details"
echo "   Host: $MYSQL_HOST"
echo "   Port: ${MYSQL_PORT:-3306}"
echo "   Database: $MYSQL_DATABASE"
echo "   User: $MYSQL_USER"
echo ""

# Check if mysql client is installed
if ! command -v mysql &> /dev/null; then
    echo "📦 MySQL client not found. Installing..."
    brew install mysql-client
    export PATH="/opt/homebrew/opt/mysql-client/bin:$PATH"
fi

# Import the database
echo "📥 Importing database (this may take a few minutes for 48MB file)..."
echo ""

DB_FILE="../database/dbxgdxdldly759.sql"

if [ ! -f "$DB_FILE" ]; then
    echo "❌ Database file not found: $DB_FILE"
    exit 1
fi

mysql -h "$MYSQL_HOST" -P "${MYSQL_PORT:-3306}" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$DB_FILE"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database imported successfully!"
    echo ""
    echo "Next steps:"
    echo "1. Update WordPress site URLs in Railway if needed"
    echo "2. Check your WordPress site at: https://primary-production-6a4bb.up.railway.app"
else
    echo ""
    echo "❌ Database import failed. Please check the error above."
    exit 1
fi

