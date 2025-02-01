<?php

namespace RoadRunner\Dispatcher;

use App\Kernel;
use Baldinof\RoadRunnerBundle\Runtime\Runtime;

use function dirname;

final class HttpDispatcher implements DispatcherInterface
{
    /**
     * @return never
     */
    public function serve(): void
    {
        $runtime = new Runtime(($_SERVER['APP_RUNTIME_OPTIONS'] ?? $_ENV['APP_RUNTIME_OPTIONS'] ?? []) + [
            'project_dir' => dirname(__DIR__, 2),
        ]);

        [$app, $args] = $runtime->getResolver(function (array $context) {
            return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
        })->resolve();

        $app = $app(...$args);

        $output = $runtime->getRunner($app)->run();

        exit($output);
    }
}
