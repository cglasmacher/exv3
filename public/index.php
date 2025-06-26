<?php

/**
 * Laravel - A PHP Framework for Web Artisans
 *
 * This file is the entry point for all HTTP requests to your application.
 */

// Define the start time constant for performance metrics
define('LARAVEL_START', microtime(true));

// Register the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Run the application through the Http Kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Send the response back to the client
$response->send();

// Terminate the kernel (run any termination middleware, etc.)
$kernel->terminate($request, $response);
