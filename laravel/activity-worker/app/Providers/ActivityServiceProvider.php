<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lagdo\Facades\AbstractFacade;
use Sample\Temporal\Factory\ClassReaderTrait;
use Temporal\Worker\WorkerInterface;

use function base_path;

class ActivityServiceProvider extends ServiceProvider
{
    use ClassReaderTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $worker = $this->app->make(WorkerInterface::class);

        // Register the activity classes.
        $directory = base_path('src/Workflow/Service/Activity');
        $namespace = 'Sample\\Workflow\\Service\\Activity';
        $classes = $this->readClasses($directory, $namespace);
        foreach($classes as $activityClass)
        {
            if(!$activityClass->isSubclassOf(AbstractFacade::class))
            {
                $activityClassName = $activityClass->getName();
                // Register the class as an activity.
                $worker->registerActivity($activityClassName,
                    fn() => $this->app->make($activityClassName));

                // Add the class in the service container.
                $this->app->bindIf($activityClassName, $activityClassName);
            }
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
