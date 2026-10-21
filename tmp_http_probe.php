<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Session;

$app = require_once __DIR__ . '/bootstrap/app.php';

$sessionId = 'probe' . bin2hex(random_bytes(8));
$cookies = [env('SESSION_COOKIE', 'laravel_session') => $sessionId];

App\Models\Admin::retrieved(function ($admin) {
    $admin->setRelation('roles', collect());
    $admin->setRelation('permissions', collect([
        new Spatie\Permission\Models\Permission(['name' => 'create_library_orders', 'guard_name' => 'admin']),
    ]));
});

$boot = Illuminate\Http\Request::create('/dashboard/orders/library-booking', 'GET', [], $cookies);
$app->instance('request', $boot);
$app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();
Session::setId($sessionId);
Session::start();
auth('admin')->loginUsingId(1);
Session::save();

function fetch(string $path, array $cookies, array $headers = [])
{
    $request = Illuminate\Http\Request::create($path, 'GET', [], $cookies, [], array_merge([
        'HTTP_ACCEPT' => 'application/json',
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
    ], $headers));
    $response = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);
    return [$response->getStatusCode(), (string) $response->headers->get('Content-Type'), (string) $response->getContent()];
}

[$code, , $html] = fetch('/dashboard/orders/library-booking?tab=statistics', $cookies, ['HTTP_ACCEPT' => 'text/html']);
echo "PAGE: $code | " . strlen($html) . " bytes\n";

preg_match('/<form id="statsFilterForm".*?<\/form>/s', $html, $m);
echo 'FORM FOUND: ' . (isset($m[0]) ? 'yes' : 'NO') . "\n";
if (isset($m[0])) {
    preg_match_all('/name="([^"]+)"/', $m[0], $names);
    echo 'FORM FIELDS: ' . implode(', ', $names[1]) . "\n";
    preg_match_all('/<option[^>]*value="([^"]*)"/', $m[0], $opts);
    echo 'OPTIONS: ' . count($opts[1]) . "\n";
    echo 'DUP product ids: ' . (count($opts[1]) - count(array_unique($opts[1]))) . "\n";
}

[$code, $ctype, $body] = fetch('/dashboard/orders/library-booking/statistics-data', $cookies);
echo "AJAX (no filters): $code | $ctype | " . strlen($body) . " | " . substr(preg_replace('/\s+/', ' ', $body), 0, 120) . "\n";

// Page script block sanity checks
$scriptStart = strrpos($html, '@endsection');
echo 'PAGE ends with @endsection: ' . (trim(substr($html, -40)) !== '' ? 'yes' : 'NO') . "\n";
echo 'Blade directives left in HTML: ' . preg_match_all('/@[a-zA-Z]+\(/', $html) . "\n";
echo '@endsection left in HTML: ' . substr_count($html, '@endsection') . "\n";
echo '@section left in HTML: ' . substr_count($html, '@section') . "\n";

// find inline page script
if (preg_match('/<script>(.*?)fetchStatistics\(\)\s*\{.*?<\/script>/s', $html, $sm)) {
    echo "PAGE SCRIPT FOUND: " . strlen($sm[0]) . " bytes\n";
} else {
    echo "PAGE SCRIPT: not matched\n";
}
echo 'select2 script tags: ' . substr_count($html, 'select2.js') . "\n";
echo 'jquery script tags: ' . substr_count($html, 'jquery/jquery.js') . "\n";
echo 'stats route url in html: ' . (strpos($html, '/dashboard/orders/library-booking/statistics-data') !== false ? 'yes' : 'NO') . "\n";

// Simulate exactly what jQuery serialize() sends from the form: period/from_date/... with product_id & status = all
[$c2, $ct2, $b2] = fetch('/dashboard/orders/library-booking/statistics-data?' . http_build_query([
    'period' => 'month', 'from_date' => '', 'to_date' => '', 'branch_id' => 'all', 'product_id' => 'all', 'status' => 'all',
]), $cookies);
echo "AJAX (serialized defaults): $c2 | $ct2 | " . strlen($b2) . " | " . substr(preg_replace('/\s+/', ' ', $b2), 0, 80) . "\n";

foreach (['today', 'week', 'year', 'custom'] as $p) {
    [$c3, $ct3, $b3] = fetch('/dashboard/orders/library-booking/statistics-data?' . http_build_query([
        'period' => $p, 'from_date' => '2026-01-01', 'to_date' => '2026-12-31', 'branch_id' => 'all', 'product_id' => 'all', 'status' => 'all',
    ]), $cookies);
    $decoded = json_decode($b3, true);
    echo "AJAX ($p): $c3 | " . strlen($b3) . " | keys=" . (is_array($decoded) ? implode(',', array_keys($decoded)) : 'not-json') . "\n";
}

// Escape-test: product names containing quotes/double quotes break the JS bootstrap script.
echo "\n=== Data attr scan in rendered page ===\n";
$badAttrs = [];
foreach (['name', 'short-name', 'brand', 'stage'] as $attr) {
    preg_match_all('/data-' . $attr . '="([^"]*)"/', $html, $m3);
    foreach ($m3[1] as $v) {
        if (strpbrk($v, "'`") !== false) {
            $badAttrs[] = "data-$attr = " . substr($v, 0, 90);
        }
    }
}
echo 'data attrs with raw quotes/backticks: ' . count($badAttrs) . "\n";
foreach (array_slice($badAttrs, 0, 8) as $b) {
    echo '  -> ' . $b . "\n";
}

// Option text (between <option ...> and </option>) with quotes -> also injected into JS via different path?
preg_match_all('/data-name="([^"]*)"/', $html, $mn);
$withDoubleQuote = array_filter($mn[1], fn($v) => str_contains($v, '"') || str_contains($v, '&quot;') || str_contains($v, '&#039;'));
echo 'data-name with quotes: ' . count($withDoubleQuote) . "\n";

echo "\n=== Branch names ===\n";
foreach (App\Models\ShippingMethod::where('type', 'branch')->get() as $sm) {
    echo "#{$sm->id}: " . substr($sm->name, 0, 60) . "\n";
}

echo "\n=== EXACT payload jQuery sends (product_id=all) ===\n";
[$c5, $ct5, $b5] = fetch('/dashboard/orders/library-booking/statistics-data?' . http_build_query([
    'period' => 'month', 'from_date' => '', 'to_date' => '', 'branch_id' => 'all', 'product_id' => 'all', 'status' => 'all',
]), $cookies);
$j5 = json_decode($b5, true);
echo "status=$c5 bytes=" . strlen($b5) . " html_len=" . strlen($j5['html'] ?? '') . "\n";
echo 'html has statsContentPlaceholder? ' . (str_contains($j5['html'] ?? '', 'statsContentPlaceholder') ? 'YES(!)' : 'no') . "\n";
echo 'html has <script>? ' . (str_contains($j5['html'] ?? '', '<script') ? 'YES(!)' : 'no') . "\n";
echo 'stats keys: ' . implode(',', array_keys($j5['stats'] ?? [])) . "\n";
echo 'branches in html: ' . substr_count($j5['html'] ?? '', 'card border shadow-sm mb-4') . "\n";

// Find the statsFilterForm opening tag and what precedes it (look for an unclosed element)
preg_match_all('/<form[^>]*>/', $html, $forms);
// Static JS syntax check of the page script with node (if available)
echo "\n=== JS SYNTAX CHECK ===\n";
$marker = '$(document).ready(function() {';
$jsStart = strpos($html, $marker);
if ($jsStart === false) {
    echo "page-script marker NOT FOUND in rendered HTML\n";
} else {
    $scriptOpen = strrpos(substr($html, 0, $jsStart), '<script>');
    $jsEnd = strpos($html, '</script>', $jsStart);
    $js = substr($html, $scriptOpen + 8, $jsEnd - $scriptOpen - 8);
    file_put_contents(__DIR__ . '/tmp_page_script.js', $js);
    echo 'extracted ' . strlen($js) . " bytes -> tmp_page_script.js\n";
}

// Element id inventory
$ids = [];
preg_match_all('/id="([^"]+)"/', $html, $idm);
foreach ($idm[1] as $i) {
    $ids[$i] = ($ids[$i] ?? 0) + 1;
}
$dupes = array_filter($ids, fn($n) => $n > 1);
echo 'duplicate ids: ' . (empty($dupes) ? 'none' : implode(', ', array_map(fn($k, $v) => "$k(x$v)", array_keys($dupes), $dupes))) . "\n";
foreach (['statsContentPlaceholder', 'statsLoadingOverlay', 'statsDataWrapper', 'tab-stats-pane', 'tab-stats-btn', 'tabStatsBadge', 'currentPeriodTitle', 'btnExportExcel', 'quickPeriodGroup', 'stats_product_id', 'stats_branch_id', 'stats_status'] as $need) {
    echo "id $need: " . ($ids[$need] ?? 0) . "\n";
}

foreach ($forms[0] as $f) {
    echo substr($f, 0, 140) . "\n";
}
preg_match_all('/<\/form>/', $html, $closeForms);
echo 'open forms: ' . count($forms[0]) . ' | close forms: ' . count($closeForms[0]) . "\n";

// Check the exact opening of the booking form and the tabs structure
$pos = strpos($html, 'id="statsFilterForm"');
echo "\nContext before statsFilterForm:\n" . substr($html, max(0, $pos - 400), 400) . "\n";


// Does #statsPeriodInput exist and what value?
if (preg_match('/id="statsPeriodInput"[^>]*/', $html, $pm)) {
    echo "\n" . $pm[0] . "\n";
}

[$c4, $ct4, $b4] = fetch('/dashboard/orders/library-booking/statistics-data?' . http_build_query([
    'period' => 'nonsense', 'from_date' => 'xx', 'to_date' => '', 'branch_id' => '999999', 'product_id' => 'abc', 'status' => 'zzz',
]), $cookies);
echo "AJAX (bad input): $c4 | $ct4 | " . strlen($b4) . " | " . substr(preg_replace('/\s+/', ' ', $b4), 0, 100) . "\n";
