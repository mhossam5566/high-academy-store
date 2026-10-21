<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$periods = [
    'month' => [],
    'year' => [],
    'custom' => ['period' => 'custom', 'from_date' => '2024-01-01', 'to_date' => '2026-12-31'],
    'today' => [],
    'week' => [],
];

$controller = app()->make(App\Http\Controllers\Admin\OrderController::class);
$method = new ReflectionMethod($controller, 'calculateLibraryBookingStats');
$method->setAccessible(true);

foreach ($periods as $key => $extra) {
    $request = Illuminate\Http\Request::create('/dashboard/orders/library-booking/statistics-data', 'GET', array_merge(['period' => $key], $extra));
    app()->instance('request', $request);
    $t0 = microtime(true);
    try {
        $stats = $method->invoke($controller, $request);
        printf("%-7s OK  t=%.2fs orders=%d books=%d revenue=%s\n", $key, microtime(true) - $t0, $stats['grand_total_orders'], $stats['grand_total_books'], $stats['grand_total_revenue']);
    } catch (Throwable $e) {
        printf("%-7s ERR %s | %s | %s:%d\n", $key, get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
    }
}
