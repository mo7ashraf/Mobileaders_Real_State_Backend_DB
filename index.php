<?php
// ABSOLUTE path to your Laravel app root:
$APP = '/home/metatry/public_html';

require $APP . '/vendor/autoload.php';
$app = require $APP . '/bootstrap/app.php';

$kernel  = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response= $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
