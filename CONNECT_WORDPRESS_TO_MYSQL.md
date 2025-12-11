# Connect WordPress to MySQL on Railway

Your WordPress Docker service needs environment variables to connect to your MySQL service.

## Steps:

1. **Go to your WordPress service** (Docker Image service) → **Variables** tab

2. **Add these variables** (Reference from MySQL service using the `{}` icon):

   - **WORDPRESS_DB_HOST** → Reference `MYSQL_PRIVATE_URL` or set to: `mysql.railway.internal:3306`
   - **WORDPRESS_DB_USER** → Reference `MYSQLUSER` or set to: `root`
   - **WORDPRESS_DB_PASSWORD** → Reference `MYSQL_ROOT_PASSWORD` or `MYSQLPASSWORD`
   - **WORDPRESS_DB_NAME** → Reference `MYSQLDATABASE` or set to: `railway`

   **OR** if WordPress uses different variable names:

   - **MYSQL_HOST** → `mysql.railway.internal`
   - **MYSQL_PORT** → `3306`
   - **MYSQL_USER** → `root`
   - **MYSQL_PASSWORD** → Reference from MySQL service
   - **MYSQL_DATABASE** → `railway`

3. **After adding variables**, Railway will automatically redeploy WordPress

4. **Check your WordPress site** - it should now connect to the database

## To verify connection:

- Visit: https://docker-image-production-e7d9.up.railway.app
- If you see WordPress setup or your content, it's connected!
- If you see database connection errors, check the variable names match what WordPress expects

