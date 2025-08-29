<?php

namespace App\RoadRunner\Dispatcher;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Runtime\SymfonyRuntime;
use Closure;

final class TemporalDispatcher extends AbstractSymfonyDispatcher
{
    /**
     * @inheritDoc
     */
    protected function getRuntime(array $runtimeOptions): SymfonyRuntime
    {
        return new SymfonyRuntime($runtimeOptions);
    }

    /**
     * @inheritDoc
     */
    protected function getResolverClosure(): Closure
    {
        return function(array $context) {
            $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
            // Set the Temporal command as the default command,
            // and set the application as a single command application.
            return (new Application($kernel))->setDefaultCommand('temporal:runtime:run', true);
        };
    }
}
