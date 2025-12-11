<?php
/**
 * Database Connection Log Checker
 * Shows connection attempts and errors
 */

echo "<h1>Database Connection Log Checker</h1>";

// Database parameters
$db_name = 'railway';
$db_user = 'root';
$db_password = 'EAEAuwZrDfldCsVlILoRIlRNrUqjTIPT';
$db_host = 'tramway.proxy.rlwy.net:24429';

$host_parts = explode(':', $db_host);
$host = $host_parts[0];
$port = isset($host_parts[1]) ? $host_parts[1] : 3306;

echo "<h2>Connection Parameters:</h2>";
echo "<pre>";
echo "Host: $host\n";
echo "Port: $port\n";
echo "Database: $db_name\n";
echo "User: $db_user\n";
echo "Password: " . str_repeat('*', strlen($db_password)) . "\n";
echo "</pre>";

echo "<h2>Connection Test:</h2>";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Try connection with detailed error reporting
$mysqli = new mysqli($host, $db_user, $db_password, $db_name, $port);

if ($mysqli->connect_error) {
    echo "<div style='background: #ffebee; padding: 20px; border: 2px solid #f44336; border-radius: 5px;'>";
    echo "<h3 style='color: #d32f2f;'>❌ CONNECTION FAILED</h3>";
    echo "<p><strong>Error Code:</strong> " . $mysqli->connect_errno . "</p>";
    echo "<p><strong>Error Message:</strong> " . $mysqli->connect_error . "</p>";
    
    // Detailed error explanations
    $error_explanations = [
        2002 => "Cannot connect to MySQL server. The hostname '$host' cannot be resolved or the server is not running.",
        2003 => "Can't connect to MySQL server on '$host:$port'. The server may be down or the port is blocked.",
        2005 => "Unknown MySQL server host '$host'. DNS resolution failed.",
        1045 => "Access denied for user '$db_user'. Wrong username or password.",
        1049 => "Unknown database '$db_name'. The database does not exist.",
        2013 => "Lost connection to MySQL server during query.",
    ];
    
    if (isset($error_explanations[$mysqli->connect_errno])) {
        echo "<p><strong>Explanation:</strong> " . $error_explanations[$mysqli->connect_errno] . "</p>";
    }
    
    echo "</div>";
    
    // Additional diagnostics
    echo "<h3>Diagnostics:</h3>";
    echo "<ul>";
    
    // DNS check
    $ip = gethostbyname($host);
    if ($ip === $host) {
        echo "<li style='color: red;'>❌ DNS Resolution: Cannot resolve '$host'</li>";
    } else {
        echo "<li style='color: green;'>✅ DNS Resolution: '$host' resolves to $ip</li>";
    }
    
    // Port check
    $connection = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($connection) {
        echo "<li style='color: green;'>✅ Port $port is open and reachable</li>";
        fclose($connection);
    } else {
        echo "<li style='color: red;'>❌ Port $port is not reachable: $errstr ($errno)</li>";
    }
    
    echo "</ul>";
    
} else {
    echo "<div style='background: #e8f5e9; padding: 20px; border: 2px solid #4caf50; border-radius: 5px;'>";
    echo "<h3 style='color: #2e7d32;'>✅ CONNECTION SUCCESSFUL!</h3>";
    echo "<p><strong>Server Info:</strong> " . $mysqli->server_info . "</p>";
    echo "<p><strong>Host Info:</strong> " . $mysqli->host_info . "</p>";
    
    // Check if database has tables
    $result = $mysqli->query("SHOW TABLES");
    if ($result) {
        $table_count = $result->num_rows;
        echo "<p><strong>Tables in database:</strong> $table_count</p>";
        if ($table_count > 0) {
            echo "<p style='color: green;'>✅ Database has been imported!</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ Database is empty - import may not have completed</p>";
        }
    }
    
    $mysqli->close();
    echo "</div>";
}

// Check WordPress debug log
echo "<h2>WordPress Debug Log:</h2>";
$debug_log = ABSPATH . 'wp-content/debug.log';
if (file_exists($debug_log)) {
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 400px; overflow: auto;'>";
    $lines = file($debug_log);
    $recent_lines = array_slice($lines, -50); // Last 50 lines
    echo htmlspecialchars(implode('', $recent_lines));
    echo "</pre>";
} else {
    echo "<p>No debug.log file found at: $debug_log</p>";
    echo "<p>Make sure WP_DEBUG_LOG is enabled in wp-config.php</p>";
}

// Check PHP error log
echo "<h2>PHP Error Log:</h2>";
$php_error_log = ini_get('error_log');
if ($php_error_log && file_exists($php_error_log)) {
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 400px; overflow: auto;'>";
    $lines = file($php_error_log);
    $recent_lines = array_slice($lines, -50);
    echo htmlspecialchars(implode('', $recent_lines));
    echo "</pre>";
} else {
    echo "<p>PHP error log not found or not configured</p>";
}

?>
