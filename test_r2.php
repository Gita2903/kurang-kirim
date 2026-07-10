<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $exists = \Illuminate\Support\Facades\Storage::disk('r2')->exists('lampiran/1777535290_Surat_Jalan_30-April-2026_TX6G.pdf');
    echo "Success: exists=" . var_export($exists, true) . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
