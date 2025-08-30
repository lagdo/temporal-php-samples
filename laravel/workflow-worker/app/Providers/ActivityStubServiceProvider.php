<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lagdo\Facades\AbstractFacade;
use Sample\Temporal\Attribute\ActivityOptions;
use Sample\Temporal\Factory\ActivityFactory;
use Sample\Temporal\Factory\ClassReaderTrait;
use Temporal\Activity\ActivityInterface;
use ReflectionClass;
use ReflectionException;

use function config;
use function count;

class ActivityStubServiceProvider extends ServiceProvider
{
    use ClassReaderTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Process the classes that are tagged as activity.
        $directories = config('temporal.register.activities');
        foreach($directories as $namespace => $directory)
        {
            $classes = $this->readClasses($directory, $namespace);
            foreach($classes as $activityClass)
            {
                $this->registerActivityStub($activityClass);
            }
        }
    }

    /**
     * @template T of object
     * @template I of object
     * @param ReflectionClass<T> $activityClass
     *
     * @return ReflectionClass<I>|null
     */
    private function getInterfaceFromFacade(ReflectionClass $activityClass): ?ReflectionClass
    {
        try
        {
            // Call the protected "getServiceIdentifier()" method of the facade to get the service id.
            $serviceIdentifierMethod = $activityClass->getMethod('getServiceIdentifier');
            $serviceIdentifierMethod->setAccessible(true);
            /** @var class-string<I> */
            $activityInterfaceName = $serviceIdentifierMethod->invoke(null);
            $activityInterface = new ReflectionClass($activityInterfaceName);

            return count($activityInterface->getAttributes(ActivityInterface::class)) === 0 ?
                null : $activityInterface;
        }
        catch(ReflectionException $_)
        {
            return null;
        }
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $activityClass
     *
     * @return void
     */
    private function registerActivityStub(ReflectionClass $activityClass): void
    {
        if(!$activityClass->isSubclassOf(AbstractFacade::class))
        {
            return;
        }
        $activityInterface = $this->getInterfaceFromFacade($activityClass);
        if($activityInterface === null)
        {
            return;
        }

        // The class is a facade on ActivityInterface. Register an activity stub.
        $activity = $activityInterface->getName();
        $this->app->singleton($activity, function() use($activityInterface) {
            $activity = $activityInterface->getName();
            // The key for the options in the DI container
            $optionsId = $this->getOptionsIdInDiContainer($activityInterface);
            $options = $this->app->make($optionsId);
            return ActivityFactory::activityStub($activity, $options);
        });
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $activityInterface
     *
     * @return string
     */
    private function getOptionsIdInDiContainer(ReflectionClass $activityInterface): string
    {
        $attributes = $activityInterface->getAttributes(ActivityOptions::class);
        return count($attributes) > 0 ?
            $attributes[0]->newInstance()->idInDiContainer :
            config('activityDefaultOptions', 'defaultActivityOptions');
    }
}
