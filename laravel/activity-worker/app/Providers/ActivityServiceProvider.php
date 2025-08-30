<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lagdo\Facades\AbstractFacade;
use Sample\Temporal\Factory\ClassReaderTrait;
use Temporal\Worker\WorkerInterface;
use ReflectionClass;

use function config;

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
        $directories = config('temporal.register.activities');
        foreach($directories as $namespace => $directory)
        {
            $classes = $this->readClasses($directory, $namespace);
            foreach($classes as $activityClass)
            {
                $this->registerActivity($worker, $activityClass);
            }
        }
    }

    /**
     * @param WorkerInterface $worker
     * @param ReflectionClass $activity
     *
     * @return void
     */
    private function registerActivity(WorkerInterface $worker, ReflectionClass $activity): void
    {
        if($activity->isSubclassOf(AbstractFacade::class))
        {
            // Do not register the service facades
            return;
        }

        $activityClassName = $activity->getName();
        // Register the class as an activity.
        $worker->registerActivity($activityClassName,
            fn() => $this->app->make($activityClassName));

        // Add the class in the service container.
        $this->app->bindIf($activityClassName, $activityClassName);
    }
}
