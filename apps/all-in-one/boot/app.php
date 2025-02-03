<?php

require __DIR__ . '/../vendor/autoload.php';

use App\RoadRunner\Dispatcher\DispatcherInterface;
use App\RoadRunner\Dispatcher\HttpDispatcher;
use App\RoadRunner\Dispatcher\TemporalDispatcher;
use App\RoadRunner\RoadRunnerMode;
use Spiral\RoadRunner\Environment;

/**
 * @var array<string, DispatcherInterface> $dispatchers
 */
$dispatchers = [
    RoadRunnerMode::Http->value => new HttpDispatcher(),
    RoadRunnerMode::Temporal->value => new TemporalDispatcher(),
];
// Create environment
$env = Environment::fromGlobals();

// Execute dispatcher that can serve the request
if (($dispatcher = $dispatchers[$env->getMode()] ?? null) !== null) {
    $dispatcher->serve();
}
