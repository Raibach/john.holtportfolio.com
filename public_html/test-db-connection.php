<?php
/**
 * Database Connection Debug Script
 * This will test the database connection and show what's wrong
 */

// Database parameters from wp-config.php
$db_name = 'railway';
$db_user = 'root';
$db_password = 'EAEAuwZrDfldCsVlILoRIlRNrUqjTIPT';
$db_host = 'mysql.railway.internal:3306';

echo "<h1>Database Connection Debug</h1>";
echo "<h2>Connection Parameters:</h2>";
echo "<pre>";
echo "DB_NAME: $db_name\n";
echo "DB_USER: $db_user\n";
echo "DB_PASSWORD: " . str_repeat('*', strlen($db_password)) . "\n";
echo "DB_HOST: $db_host\n";
echo "</pre>";

// Parse host and port
$host_parts = explode(':', $db_host);
$host = $host_parts[0];
$port = isset($host_parts[1]) ? $host_parts[1] : 3306;

echo "<h2>Parsed Connection Details:</h2>";
echo "<pre>";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $db_name\n";
echo "User: $db_user\n";
echo "</pre>";

echo "<h2>Connection Test:</h2>";

// Test 1: Check if we can resolve the hostname
echo "<h3>1. DNS Resolution Test:</h3>";
$ip = gethostbyname($host);
if ($ip === $host) {
    echo "<p style='color:red;'>❌ FAILED: Cannot resolve hostname '$host'</p>";
} else {
    echo "<p style='color:green;'>✅ SUCCESS: Hostname resolves to $ip</p>";
}

// Test 2: Try to connect
echo "<h3>2. MySQL Connection Test:</h3>";
$mysqli = @new mysqli($host, $db_user, $db_password, $db_name, $port);

if ($mysqli->connect_error) {
    echo "<p style='color:red;'>❌ CONNECTION FAILED</p>";
    echo "<pre>Error: " . $mysqli->connect_error . "\n";
    echo "Error Code: " . $mysqli->connect_errno . "</pre>";
    
    // Common error codes
    $error_codes = [
        2002 => "Cannot connect to MySQL server (wrong host or server not running)",
        2005 => "Unknown MySQL server host",
        1045 => "Access denied (wrong username or password)",
        1049 => "Unknown database (database doesn't exist)",
        2013 => "Lost connection to MySQL server during query",
    ];
    
    if (isset($error_codes[$mysqli->connect_errno])) {
        echo "<p><strong>Meaning:</strong> " . $error_codes[$mysqli->connect_errno] . "</p>";
    }
} else {
    echo "<p style='color:green;'>✅ CONNECTION SUCCESSFUL!</p>";
    echo "<pre>MySQL Server Info: " . $mysqli->server_info . "\n";
    echo "Host Info: " . $mysqli->host_info . "</pre>";
    
    // Test query
    $result = $mysqli->query("SHOW TABLES");
    if ($result) {
        $table_count = $result->num_rows;
        echo "<p style='color:green;'>✅ Database has $table_count tables</p>";
    }
    
    $mysqli->close();
}

// Test 3: Environment variables check
echo "<h3>3. Environment Variables Check:</h3>";
$env_vars = ['MYSQLDATABASE', 'MYSQLUSER', 'MYSQL_ROOT_PASSWORD', 'MYSQLPASSWORD', 'MYSQL_PRIVATE_URL'];
echo "<pre>";
foreach ($env_vars as $var) {
    $value = getenv($var);
    if ($value) {
        echo "$var: " . (strpos($var, 'PASSWORD') !== false ? str_repeat('*', strlen($value)) : $value) . "\n";
    } else {
        echo "$var: (not set)\n";
    }
}
echo "</pre>";

echo "<h2>Recommendations:</h2>";
echo "<ul>";
echo "<li>Verify the MySQL service is running in Railway</li>";
echo "<li>Check that the database name, username, and password match Railway's MySQL service variables</li>";
echo "<li>Ensure the WordPress service can reach mysql.railway.internal (internal Railway network)</li>";
echo "<li>If using environment variables, make sure they're set in Railway's WordPress service</li>";
echo "</ul>";
