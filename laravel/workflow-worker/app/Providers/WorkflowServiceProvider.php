<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lagdo\Facades\AbstractFacade;
use Sample\Temporal\Factory\ClassReaderTrait;
use Temporal\Worker\WorkerInterface;

use function base_path;
use function count;

class WorkflowServiceProvider extends ServiceProvider
{
    use ClassReaderTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $worker = $this->app->make(WorkerInterface::class);

        // Register the workflow classes.
        $workflows = [];
        $directory = base_path('src/Workflow/Service/Workflow');
        $namespace = 'Sample\\Workflow\\Service\\Workflow';
        $classes = $this->readClasses($directory, $namespace);
        foreach($classes as $workflowClass)
        {
            if(!$workflowClass->isSubclassOf(AbstractFacade::class))
            {
                $workflows[] = $workflowClass->getName();
            }
        }

        // Register the workflow classes
        if(count($workflows) > 0)
        {
            $worker->registerWorkflowTypes(...$workflows);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
