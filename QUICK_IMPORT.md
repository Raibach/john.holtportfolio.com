# Quick Database Import Guide

## Step 1: Login to Railway CLI

Open your terminal and run:

```bash
cd /Users/raibach/Documents/websites/holtportfolio.com
railway login
```

This will open a browser window for you to authenticate.

## Step 2: Run the Import Script

After logging in, run:

```bash
./import-database.sh
```

The script will:
- ✅ Check if you're logged in
- ✅ Link to your Railway project (if needed)
- ✅ Get MySQL connection details automatically
- ✅ Install MySQL client if needed
- ✅ Import the 48MB database file

## Alternative: Manual Import

If the script doesn't work, you can import manually:

1. Get connection details:
```bash
railway variables | grep MYSQL
```

2. Install MySQL client (if needed):
```bash
brew install mysql-client
export PATH="/opt/homebrew/opt/mysql-client/bin:$PATH"
```

3. Import database:
```bash
mysql -h [MYSQLHOST] -P [MYSQLPORT] -u [MYSQLUSER] -p[MYSQLPASSWORD] [MYSQLDATABASE] < ../database/dbxgdxdldly759.sql
```

Replace the bracketed values with your actual Railway MySQL credentials.

