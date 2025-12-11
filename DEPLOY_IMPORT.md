# Deploy Database Import Service to Railway

Since Railway blocks external database connections, we'll deploy a temporary service INSIDE Railway that can access the database via Railway's internal network.

## Steps:

1. **In Railway Dashboard:**
   - Go to your project
   - Click "+ New" → "GitHub Repo" or "Empty Service"
   - Name it: `database-import`

2. **Connect to this repository:**
   - Repository: `Raibach/john.holtportfolio.com`
   - Root Directory: `railway-import` (if we add it to the repo)
   - Or use "Deploy from Dockerfile"

3. **Add the SQL file:**
   - The service needs access to `dbxgdxdldly759.sql`
   - We can add it to the repo or use Railway volumes

4. **Set Environment Variables:**
   - Reference `MARIADB_PASSWORD` from your MariaDB service
   - The service will use `mariadb.railway.internal` to connect

5. **Deploy:**
   - Railway will run the import automatically
   - Check logs to confirm import success
   - Delete the service after import completes

## Alternative: Use Railway's Volume System

If Railway supports volumes, we can:
1. Upload SQL file to a Railway volume
2. Mount volume in a temporary service
3. Import from mounted volume

Let me know which approach you prefer!

