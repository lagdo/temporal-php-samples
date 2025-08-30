<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lagdo\Facades\AbstractFacade;
use Sample\Temporal\Factory\ClassReaderTrait;
use Temporal\Worker\WorkerInterface;
use ReflectionClass;

use function array_filter;
use function array_map;
use function config;
use function count;

class ChildWorkflowServiceProvider extends ServiceProvider
{
    use ClassReaderTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the classes that are tagged as child workflow.
        $directories = config('temporal.register.child_workflows');
        foreach($directories as $namespace => $directory)
        {
            $classes = $this->readClasses($directory, $namespace);
            $workflows = array_filter($classes, fn(ReflectionClass $class): bool =>
                !$class->isSubclassOf(AbstractFacade::class));
            if(count($workflows) === 0)
            {
                continue;
            }

            // Register the workflow classes
            $workflows = array_map(fn(ReflectionClass $workflow): string =>
                $workflow->getName(), $workflows);
            $worker = $this->app->make(WorkerInterface::class);
            $worker->registerWorkflowTypes(...$workflows);
        }
    }
}
