<?php

use Illuminate\Support\Facades\Artisan;
use Sample\Temporal\Runtime\RuntimeInterface;

Artisan::command('temporal:runtime:run', function (RuntimeInterface $runtime) {
    $runtime->run();
})->purpose('Run the worker app');
