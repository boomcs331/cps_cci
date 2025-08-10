<?php
/**
 * Database Setup Script
 * Run this script to set up the database tables and sample data
 */

require_once 'config/config.php';

try {
    // Create database connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Database Setup</h1>";
    echo "<p>Setting up database for CPS (Computer Production System)...</p>";
    
    // Read and execute the schema file
    $schema_file = 'database/schema.sql';
    if (file_exists($schema_file)) {
        $sql = file_get_contents($schema_file);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                    echo "<p style='color: green;'>✓ Executed: " . substr($statement, 0, 50) . "...</p>";
                } catch (PDOException $e) {
                    echo "<p style='color: orange;'>⚠ Warning: " . $e->getMessage() . "</p>";
                }
            }
        }
        
        echo "<h2>Setup Complete!</h2>";
        echo "<p>The database has been set up successfully.</p>";
        echo "<h3>Sample Users:</h3>";
        echo "<ul>";
        echo "<li><strong>admin001</strong> - System Administrator (admin role)</li>";
        echo "<li><strong>pc001</strong> - Production Controller (pc role)</li>";
        echo "<li><strong>user001</strong> - Regular User (user role)</li>";
        echo "</ul>";
        echo "<p>You can now test the login system with these user IDs.</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Schema file not found: $schema_file</p>";
    }
    
} catch (PDOException $e) {
    echo "<h1>Database Connection Error</h1>";
    echo "<p style='color: red;'>❌ Connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in <code>config/config.php</code></p>";
}
?> 