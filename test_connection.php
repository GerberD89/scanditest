<?php

$hostname = "fdb1034.awardspace.net"; // e.g., localhost
$username = "4435104_testdb";
$password = "Root50!!089";
$database = "4435104_testdb";

// Attempt to establish a connection
$connection = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

echo "Connected successfully";

// Close the connection
$connection->close();

?>
