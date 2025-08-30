<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lagdo\Facades\AbstractFacade;
use Sample\Temporal\Attribute\ChildWorkflowOptions;
use Sample\Temporal\Factory\ChildWorkflowFactory;
use Sample\Temporal\Factory\ClassReaderTrait;
use Temporal\Workflow\WorkflowInterface;
use ReflectionClass;
use ReflectionException;

use function base_path;
use function config;
use function count;

class ChildWorkflowStubServiceProvider extends ServiceProvider
{
    use ClassReaderTrait;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the workflow classes.
        $directory = base_path('src/Workflow/Service/ChildWorkflow');
        $namespace = 'Sample\\Workflow\\Service\\ChildWorkflow';
        $classes = $this->readClasses($directory, $namespace);
        foreach($classes as $workflowClass)
        {
            if($workflowClass->isSubclassOf(AbstractFacade::class) &&
                ($workflowInterface = $this->getInterfaceFromFacade($workflowClass)) !== null)
            {
                // The class is a facade on WorkflowInterface. Register a child workflow stub.
                $this->registerChildWorkflowStub($workflowInterface);
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

    /**
     * @template T of object
     * @template I of object
     * @param ReflectionClass<T> $workflowClass
     *
     * @return ReflectionClass<I>|null
     */
    private function getInterfaceFromFacade(ReflectionClass $workflowClass): ?ReflectionClass
    {
        try
        {
            // Call the protected "getServiceIdentifier()" method of the facade to get the service id.
            $serviceIdentifierMethod = $workflowClass->getMethod('getServiceIdentifier');
            $serviceIdentifierMethod->setAccessible(true);
            /** @var class-string<I> */
            $workflowInterfaceName = $serviceIdentifierMethod->invoke(null);
            $workflowInterface = new ReflectionClass($workflowInterfaceName);

            return count($workflowInterface->getAttributes(WorkflowInterface::class)) === 0 ?
                null : $workflowInterface;
        }
        catch(ReflectionException $_)
        {
            return null;
        }
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $workflowInterface
     *
     * @return void
     */
    private function registerChildWorkflowStub(ReflectionClass $workflowInterface): void
    {
        $workflow = $workflowInterface->getName();
        $this->app->singleton($workflow, function() use($workflowInterface) {
            $workflow = $workflowInterface->getName();
            // The key for the options in the DI container
            $optionsId = $this->getOptionsIdInDiContainer($workflowInterface);
            $options = $this->app->make($optionsId);
            return ChildWorkflowFactory::childWorkflowStub($workflow, $options);
        });
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $workflowInterface
     *
     * @return string
     */
    private function getOptionsIdInDiContainer(ReflectionClass $workflowInterface): string
    {
        $attributes = $workflowInterface->getAttributes(ChildWorkflowOptions::class);
        return count($attributes) > 0 ?
            $attributes[0]->newInstance()->idInDiContainer :
            config('childWorkflowDefaultOptions', 'defaultChildWorkflowOptions');
    }
}
