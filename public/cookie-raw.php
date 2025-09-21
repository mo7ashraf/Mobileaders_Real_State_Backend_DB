<?php
setcookie('raw_cookie', 'hello', [
  'expires'  => time() + 3600,
  'path'     => '/',
  'secure'   => true,
  'httponly' => true,
  'samesite' => 'Lax',
]);
header('Content-Type: text/plain; charset=utf-8');
echo "raw ok\n";
