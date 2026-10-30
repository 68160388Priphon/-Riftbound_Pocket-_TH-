<?php
$cfg = require __DIR__ . '/db.local.php';

$conn = mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name']);

if (!$conn) {
    error_log(mysqli_connect_error());
    http_response_code(500);
    exit('Database unavailable');
}

mysqli_set_charset($conn, 'utf8mb4');
