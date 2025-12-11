#!/bin/bash

# Database Import Script - Just provide Railway MySQL details
# Usage: ./import-with-details.sh [HOST] [PORT] [USER] [PASSWORD] [DATABASE]

MYSQL_BIN="/opt/homebrew/opt/mysql-client/bin/mysql"
DB_FILE="../database/dbxgdxdldly759.sql"

if [ $# -lt 5 ]; then
    echo "Usage: $0 [MYSQL_HOST] [MYSQL_PORT] [MYSQL_USER] [MYSQL_PASSWORD] [MYSQL_DATABASE]"
    echo ""
    echo "Get these values from Railway Dashboard → MySQL Service → Variables tab:"
    echo "  - MYSQLHOST"
    echo "  - MYSQLPORT (usually 3306)"
    echo "  - MYSQLUSER"
    echo "  - MYSQLPASSWORD"
    echo "  - MYSQLDATABASE"
    exit 1
fi

MYSQL_HOST=$1
MYSQL_PORT=$2
MYSQL_USER=$3
MYSQL_PASSWORD=$4
MYSQL_DATABASE=$5

echo "📥 Importing database to Railway MySQL..."
echo "   Host: $MYSQL_HOST"
echo "   Database: $MYSQL_DATABASE"
echo ""

$MYSQL_BIN -h "$MYSQL_HOST" -P "$MYSQL_PORT" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < "$DB_FILE"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database imported successfully!"
else
    echo ""
    echo "❌ Import failed. Please check the connection details."
    exit 1
fi

