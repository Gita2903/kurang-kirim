<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Fix for hosts that disable putenv() (e.g. InfinityFree)
|--------------------------------------------------------------------------
| Load .env using only $_ENV and $_SERVER adapters, skipping putenv().
*/
$basePath = dirname(__DIR__);
$envFile = $basePath . '/.env';

if (file_exists($envFile) && !function_exists('putenv_disabled_check')) {
    // Test if putenv is actually disabled
    $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
    if (in_array('putenv', $disabled)) {
        $repository = \Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
            ->addAdapter(\Dotenv\Repository\Adapter\EnvConstAdapter::class)
            ->addAdapter(\Dotenv\Repository\Adapter\ServerConstAdapter::class)
            ->immutable()
            ->make();

        \Dotenv\Dotenv::create($repository, $basePath)->safeLoad();
    }
}

return Application::configure(basePath: $basePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

