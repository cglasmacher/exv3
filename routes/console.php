<?php

use Illuminate\Support\Facades\Artisan;

/**
 * Here is where you may define all of your Closure based console commands.
 * Each Closure is bound to a command instance allowing a simple approach
 * to interacting with each command’s IO methods without needing to create
 * a full command class.
 */

Artisan::command('inspire', function () {
    $this->comment(Illuminate\Foundation\Inspiring::quote());
})->describe('Display an inspiring quote');
