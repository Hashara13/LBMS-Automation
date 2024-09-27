<?php

// Database connection details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'libraryms';

// Create a connection
$db = new mysqli($host, $user, $password, $database);

// Check if the connection failed
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

