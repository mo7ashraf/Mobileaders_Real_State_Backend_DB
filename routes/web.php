<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ListingWebController;
use App\Http\Controllers\Web\SellerWebController;
use App\Http\Controllers\Web\SearchWebController;
use App\Http\Controllers\Web\PageWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\ProfileWebController;
use App\Http\Controllers\Web\AdsWebController;
use App\Http\Controllers\PolicyImportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


Route::get('/',                 [HomeController::class, 'index'])->name('web.home');
Route::get('/search',           [SearchWebController::class, 'index'])->name('web.search');
Route::get('/listings/{id}',    [ListingWebController::class, 'show'])->name('web.listing.show');
Route::get('/sellers',          [SellerWebController::class, 'index'])->name('web.sellers.index');
Route::get('/sellers/{id}',     [SellerWebController::class, 'show'])->name('web.seller.show');

Route::get('/policies',         [PageWebController::class, 'policiesIndex'])->name('web.policies.index');
Route::get('/policies/{slug}',  [PageWebController::class, 'policy'])->name('web.policies.show');

Route::get('/__import-policies', [PolicyImportController::class,'importFromFiles']); // visit once, then remove
Route::get('/tools/import-policies', [PolicyImportController::class, 'run'])->name('tools.import-policies');


Route::get('/support',          [PageWebController::class, 'support'])->name('web.support');

// Simple health page
Route::get('/ping', fn() => view('web.ping'));

// Auth (web session)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthWebController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthWebController::class, 'login'])->name('web.login.post');
    Route::get('/register', [AuthWebController::class, 'showRegister'])->name('web.register');
    Route::post('/register',[AuthWebController::class, 'register'])->name('web.register.post');
});
Route::post('/logout', [AuthWebController::class, 'logout'])->middleware('auth')->name('web.logout');

// Account + Ads
Route::middleware('auth')->group(function () {
    Route::get('/account',        [ProfileWebController::class, 'show'])->name('web.account');
    Route::post('/account',       [ProfileWebController::class, 'update'])->name('web.account.update');

    Route::get('/ads/new',        [AdsWebController::class, 'create'])->name('web.ads.create');
    Route::post('/ads',           [AdsWebController::class, 'store'])->name('web.ads.store');
    Route::get('/account/listings',[AdsWebController::class, 'mine'])->name('web.ads.mine');
    Route::post('/ads/{id}/delete',[AdsWebController::class, 'destroy'])->name('web.ads.delete');
});
// temporary “flush” route to clear cached routes/config/views if needed
Route::get('/__flush', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return 'flushed';
});

// A) Session persists?
Route::get('/tools/session-set', function () {
    session(['_probe' => (string) Str::uuid()]);
    return ['set' => session('_probe')];
});
Route::get('/tools/session-get', function () {
    return ['get' => session('_probe')];
});

// B) CSRF works through web middleware?
Route::match(['get','post'], '/tools/csrf', function (Request $r) {
    if ($r->isMethod('post')) return 'csrf-ok';
    return <<<HTML
<!doctype html><meta charset="utf-8">
<form method="post" action="/tools/csrf">
<input type="hidden" name="_token" value="{$r->session()->token()}">
<button>POST</button></form>
HTML;
});

Route::get('/tools/where', fn() => base_path());

// show session config + request security
Route::get('/tools/session-debug', function (Request $r) {
    return response()->json([
        'isSecure'      => $r->isSecure(),
        'xfwd_proto'    => $r->header('X-Forwarded-Proto'),
        'cookie_name'   => config('session.cookie'),
        'driver'        => config('session.driver'),
        'domain'        => config('session.domain'),
        'secure'        => config('session.secure'),
        'same_site'     => config('session.same_site'),
    ]);
});

Route::get('/tools/session-db-write', function () {
    try {
        DB::table('sessions')->insert([
            'id' => (string) Str::uuid(),
            'payload' => base64_encode(serialize(['_token'=>'x'])),
            'last_activity' => time(),
        ]);
        return 'db insert ok';
    } catch (\Throwable $e) {
        return 'db insert FAIL: '.$e->getMessage();
    }
});

Route::get('/tools/cookie-test', function () {
    return response('ok')
      ->cookie('plain_test_cookie', 'hello', 60, '/', null, true, true, false, 'Lax');
});
Route::middleware('web')->get('/tools/cookie-test2', function () {
    return response('ok web')
        ->cookie('cookie_web_group', 'yes', 60, '/', null, true, true, false, 'Lax');
});

Route::get('/tools/cookie-double', function () {
    // raw PHP cookie (inside Laravel)
    setcookie('raw_in_laravel', '1', [
      'expires' => time()+3600, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Lax',
    ]);

    // Laravel cookie
    return response('ok double')
        ->cookie('laravel_cookie', '1', 60, '/', null, true, true, false, 'Lax');
});

Route::get('/tools/session-force', function () {
    session(['_probe' => (string) Str::uuid()]);
    return response('forced')
        ->cookie(config('session.cookie'), session()->getId(), 120, '/', null, true, true, false, 'Lax');
});
Route::get('/tools/headers-sent', function () {
    $file = null; $line = null;
    $sent = headers_sent($file, $line);
    return response()->json([
        'headers_sent' => $sent,
        'file'         => $file,
        'line'         => $line,
    ]);
});

Route::get('/tools/response-headers', function () {
    $resp = response('headers');
    $resp->headers->set('X-Debug-Probe', '1');
    $resp->headers->setCookie(cookie('probe_cookie', 'ok', 60, '/', null, true, true, false, 'Lax'));
    return $resp;
});
Route::get('/tools/strip-bom', function () {
    $files = [
        base_path('routes/api.php'),
        base_path('routes/web.php'),
        app_path('Http/Kernel.php'),
        app_path('Providers/RouteServiceProvider.php'),
    ];
    $fixed = [];
    foreach ($files as $f) {
        $raw = file_get_contents($f);
        if (substr($raw, 0, 3) === "\xEF\xBB\xBF") {
            file_put_contents($f, substr($raw, 3));
            $fixed[] = $f;
        }
        // also remove accidental leading whitespace
        if (preg_match('/^\s+<\?php/s', $raw)) {
            $raw2 = preg_replace('/^\s+<\?php/s', '<?php', $raw, 1);
            if ($raw2 !== $raw) { file_put_contents($f, $raw2); $fixed[] = $f; }
        }
    }
    return ['fixed' => array_values(array_unique($fixed))];
});
Route::get('/tools/test-listings', function () {
    try {
        $count = \App\Models\Listing::count();
        $one = \App\Models\Listing::select('id','title','city','createdAt')->latest('createdAt')->first();
        return response()->json(['ok'=>true,'count'=>$count,'sample'=>$one]);
    } catch (\Throwable $e) {
        return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
    }
});
Route::get('/tools/logs', function () {
    $path = storage_path('logs/laravel.log');
    if (!is_file($path)) return 'no laravel.log';
    $tail = 250; // lines
    $lines = explode("\n", trim(@shell_exec("tail -n {$tail} " . escapeshellarg($path)) ?? ''));
    if (!$lines) {
        $data = file($path);
        $lines = array_slice($data, max(0, count($data) - $tail));
    }
    return response('<pre style="white-space:pre-wrap">'.e(implode("\n", $lines)).'</pre>');
});
Route::get('/tools/php-ext', fn() => [
  'php' => PHP_VERSION,
  'intl_loaded' => extension_loaded('intl'),
]);
Route::get('/tools/php-ini', function () {
    return response()->json([
        'php'        => PHP_VERSION,
        'sapi'       => php_sapi_name(),
        'loaded_ini' => php_ini_loaded_file(),
        'scan_dir'   => php_ini_scanned_files(),  // semicolon-separated list
        'ext_dir'    => ini_get('extension_dir'),
        'has_intl'   => extension_loaded('intl'),
        'exts'       => get_loaded_extensions(),
    ]);
});
Route::get('/tools/publish-filament-tables', function () {
    Artisan::call('vendor:publish', ['--tag' => 'filament-tables-views', '--force' => true]);
    return nl2br(e(Artisan::output()));
});
