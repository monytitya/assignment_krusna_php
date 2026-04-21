<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'mony2024**2000');
define('DB_NAME', 'Student_ass');
define('DB_PORT', 3308);

define('UPLOAD_DIR', __DIR__ . '/uploads/');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");