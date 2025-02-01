<?php

require __DIR__ . '/../vendor/autoload.php';

use RoadRunner\Dispatcher\DispatcherInterface;
use RoadRunner\Dispatcher\HttpDispatcher;
use RoadRunner\Dispatcher\TemporalDispatcher;
use RoadRunner\RoadRunnerMode;
use Spiral\RoadRunner\Environment;

/**
 * Collect all dispatchers.
 *
 * @var array<string, DispatcherInterface> $dispatchers
 */
$dispatchers = [
    RoadRunnerMode::Http->value => new HttpDispatcher(),
    RoadRunnerMode::Temporal->value => new TemporalDispatcher(),
];

// Create environment
$env = Environment::fromGlobals();

// Execute dispatcher that can serve the request
if (isset($dispatchers[$env->getMode()])) {
    $dispatcher = $dispatchers[$env->getMode()];
    $dispatcher->serve();
}
