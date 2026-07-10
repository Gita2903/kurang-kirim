<?php
// Test file — hapus setelah debug selesai!

echo "<h2>Server Info</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

echo "<h2>Disabled Functions</h2>";
$disabled = ini_get('disable_functions');
if ($disabled) {
    $funcs = explode(',', $disabled);
    $critical = ['putenv', 'getenv', 'proc_open', 'proc_close', 'symlink', 'exec', 'shell_exec'];
    foreach ($critical as $fn) {
        $found = in_array(trim($fn), array_map('trim', $funcs));
        echo "<p>" . ($found ? '❌ DISABLED' : '✅ OK') . " — $fn</p>";
    }
    echo "<hr><p><small>All disabled: $disabled</small></p>";
} else {
    echo "<p>✅ No functions disabled</p>";
}

echo "<h2>Laravel Bootstrap Test</h2>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "<p>✅ Autoload OK</p>";

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "<p>✅ App bootstrap OK</p>";

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "<p>✅ Kernel OK</p>";
} catch (\Throwable $e) {
    echo "<p>❌ ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>File: " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
