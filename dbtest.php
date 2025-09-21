<?php
$dsn = 'mysql:host=localhost;dbname=metatry_real_state' . getenv('DB_DATABASE') . ';charset=utf8mb4';
try {
  $pdo = new PDO($dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'), [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  ]);
  echo "OK: connected as " . getenv('DB_USERNAME');
} catch (Throwable $e) {
  echo "FAIL: " . $e->getMessage();
}
