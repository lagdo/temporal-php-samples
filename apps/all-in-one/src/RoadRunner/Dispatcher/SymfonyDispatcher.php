<?php

namespace App\RoadRunner\Dispatcher;

use Symfony\Component\Runtime\SymfonyRuntime;
use Closure;

use function dirname;

abstract class SymfonyDispatcher implements DispatcherInterface
{
    /**
     * Get the Symfony runtime
     *
     * @param array $runtimeOptions
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
     * @return array
     */
    private function getRuntimeOptions(): array
    {
        return ($_SERVER['APP_RUNTIME_OPTIONS'] ?? $_ENV['APP_RUNTIME_OPTIONS'] ?? []) + [
            'project_dir' => dirname(__DIR__, 3),
        ];
    }

    /**
     * @return void
     */
    public function serve(): void
    {
        $runtime = $this->getRuntime($this->getRuntimeOptions());
        [$app, $args] = $runtime->getResolver($this->getResolverClosure())->resolve();
        $app = $app(...$args);

        exit($runtime->getRunner($app)->run());
    }
}
