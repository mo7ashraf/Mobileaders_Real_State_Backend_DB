<?php
// set your one-time secret here:
const TOKEN = 'peek_9472';

// protect it:
if (($_GET['token'] ?? '') !== TOKEN) { http_response_code(403); exit('forbidden'); }

// path to laravel log:
$log = __DIR__ . '/storage/logs/laravel.log';
if (!is_file($log)) { exit("No laravel.log found at $log"); }

// tail last ~200 lines
$lines = 200;
$f = fopen($log, 'r');
$pos = -1; $buffer = '';
$stat = fstat($f); $size = $stat['size'];
$lineCount = 0;
while ($lines > $lineCount && -$pos < $size) {
  fseek($f, $pos--, SEEK_END);
  $ch = fgetc($f);
  $buffer = $ch . $buffer;
  if ($ch === "\n") $lineCount++;
}
fclose($f);
header('Content-Type: text/plain; charset=utf-8');
echo $buffer;
