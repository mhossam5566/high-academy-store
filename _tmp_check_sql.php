<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$query = App\Models\Product::query()
    ->when(true, fn($q) => $q->where('category_id', 5))
    ->orderByAvailability('desc')
    ->orderBy('created_at', 'desc');

echo $query->toSql(), PHP_EOL;
echo json_encode($query->getBindings(), JSON_UNESCAPED_UNICODE), PHP_EOL;
