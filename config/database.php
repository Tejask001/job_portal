<?php
$env = parse_ini_file('.env');

$host = $env["HOST"];
$dbname = $env["DB_NAME"];
$username = $env["DB_USERNAME"];
$password = $env["DB_PWD"];
$port = $env["PORT"];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected to the database!"; // For testing
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die(); // Terminate script if connection fails
}

// Function to prevent XSS attacks
function html_escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
