<?php
$base = __DIR__ . '/storage/framework';
@mkdir($base . '/sessions', 0775, true);
@mkdir($base . '/cache', 0775, true);
@mkdir($base . '/views', 0775, true);
@chmod($base . '/sessions', 0775);
@chmod($base . '/cache', 0775);
@chmod($base . '/views', 0775);
echo "ok";
