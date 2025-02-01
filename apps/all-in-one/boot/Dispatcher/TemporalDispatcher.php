<?php

namespace Boot\Dispatcher;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Runtime\SymfonyRuntime;

final class TemporalDispatcher implements DispatcherInterface
{
    /**
     * @return void
     */
    public function serve(): void
    {
        $runtime = new SymfonyRuntime(($_SERVER['APP_RUNTIME_OPTIONS'] ?? $_ENV['APP_RUNTIME_OPTIONS'] ?? []) + [
            'project_dir' => dirname(__DIR__, 2),
        ]);

        [$app, $args] = $runtime->getResolver(function (array $context) {
            $kernel = new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
            // Set the Temporal command as the default command,
            // and set the application as a single command application.
            return (new Application($kernel))->setDefaultCommand('temporal:runtime:run', true);
        })->resolve();
        $app = $app(...$args);
        $output = $runtime->getRunner($app)->run();

        exit($output);
    }
}
