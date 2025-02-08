<?php

namespace App\RoadRunner\Dispatcher;

use Symfony\Component\Runtime\SymfonyRuntime;
use Closure;

use function dirname;

abstract class AbstractSymfonyDispatcher implements DispatcherInterface
{
    /**
     * Get the Symfony runtime
     *
     * @param array<string,string|int> $runtimeOptions
     *
     * @return SymfonyRuntime
     */
    abstract protected function getRuntime(array $runtimeOptions): SymfonyRuntime;

    /**
     * Get the Symfony resolver closure
     *
     * @return Closure
     */
    abstract protected function getResolverClosure(): Closure;

    /**
     * @return void
     */
    public function serve(): void
    {
        $runtimeOptions = $_SERVER['APP_RUNTIME_OPTIONS'] ?? $_ENV['APP_RUNTIME_OPTIONS'] ?? [];
        $runtimeOptions['project_dir'] = dirname(__DIR__, 3);

        $runtime = $this->getRuntime($runtimeOptions);
        [$app, $args] = $runtime->getResolver($this->getResolverClosure())->resolve();
        $app = $app(...$args);

        exit($runtime->getRunner($app)->run());
    }
}
