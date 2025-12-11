# Import WordPress Database to Railway MariaDB

## Step 1: Get Railway Database Connection Details

1. Go to Railway Dashboard: https://railway.app
2. Open your WordPress project
3. Click on your **MySQL/MariaDB service**
4. Go to **Variables** tab
5. Note these values:
   - `MYSQLDATABASE` (database name)
   - `MYSQLUSER` (username)
   - `MYSQLPASSWORD` (password)
   - `MYSQLHOST` (hostname, usually something like `containers-us-west-xxx.railway.app`)
   - `MYSQLPORT` (port, usually 3306)

## Step 2: Install MySQL Client (if needed)

```bash
# macOS
brew install mysql-client

# Or use Railway CLI to connect
```

## Step 3: Import Database

### Option A: Using MySQL Command Line

```bash
cd /Users/raibach/Documents/websites

# Import the database (use dbxgdxdldly759.sql - 48MB)
mysql -h [MYSQLHOST] -P [MYSQLPORT] -u [MYSQLUSER] -p[MYSQLPASSWORD] [MYSQLDATABASE] < database/dbxgdxdldly759.sql
```

### Option B: Using Railway CLI (Easier)

```bash
# 1. Login to Railway
railway login

# 2. Link to your project
cd /Users/raibach/Documents/websites/holtportfolio.com
railway link

# 3. Connect to MySQL service
railway connect mysql

# 4. In the MySQL prompt, import:
mysql> use [your_database_name];
mysql> source /Users/raibach/Documents/websites/database/dbxgdxdldly759.sql;
```

### Option C: Using Railway's Web Terminal

1. In Railway dashboard → MySQL service → **Connect** tab
2. Click **"Open Terminal"**
3. Run:
```bash
mysql -u $MYSQLUSER -p$MYSQLPASSWORD $MYSQLDATABASE < /path/to/dbxgdxdldly759.sql
```

## Step 4: Update wp-config.php on Railway

After importing, update Railway's `wp-config.php` with:
- Database name from Railway
- Database credentials from Railway
- Database host from Railway

## Important Notes

- The database file is 48MB (20,464 lines), so import may take a few minutes
- Make sure to use the correct database name from Railway
- Update URLs in the database if domain changed (see below)

## Update Site URLs (if domain changed)

After import, you may need to update URLs:

```sql
UPDATE wp_options SET option_value = 'https://primary-production-6a4bb.up.railway.app' WHERE option_name = 'siteurl';
UPDATE wp_options SET option_value = 'https://primary-production-6a4bb.up.railway.app' WHERE option_name = 'home';
```

