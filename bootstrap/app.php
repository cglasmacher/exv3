<?php
file_put_contents(dirname(__DIR__).'/storage/boot-bootstrap.log', "Reached bootstrap/app.php\n", FILE_APPEND);
// bootstrap/app.php

use Illuminate\Foundation\Application;

/**
 * Create The Application
 *
 * Here we will load the environment and create the application instance
 * that serves as the "glue" for all the components of Laravel, and is
 * the IoC container for the system binding all of the various parts.
 */

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/**
 * Bind Important Interfaces
 *
 * Next, we need to bind some important interfaces into the container so
 * we will be able to resolve them when needed. The kernels serve the
 * incoming requests to this application from both the web and CLI.
 */

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/**
 * Return The Application
 *
 * This script returns the application instance. The instance is given to
 * the calling script so we can separate the building of the instances
 * from the actual running of the application and sending responses.
 */

return $app;
