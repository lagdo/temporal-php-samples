<?php

namespace App\Temporal\Compiler;

use App\Temporal\Attribute\WorkflowOptions;
use App\Temporal\Factory\WorkflowFactory;
use Lagdo\Facades\AbstractFacade;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Workflow\WorkflowInterface;

use function count;
use function is_string;

class WorkflowStubCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        // Process the classes that are tagged as workflow.
        /** @var array<class-string,mixed> */
        $workflows = $container->findTaggedServiceIds('temporal.service.workflow');
        foreach($workflows as $workflowClassName => $_)
        {
            $workflowClass = new ReflectionClass($workflowClassName);
            if($workflowClass->isSubclassOf(AbstractFacade::class))
            {
                // A facade doesn't need to be registered in the service container.
                $container->removeDefinition($workflowClassName);

                if(($workflowInterface = $this->getInterfaceFromFacade($workflowClass)) !== null)
                {
                    // The class is a facade on WorkflowInterface. Register a workflow stub.
                    $this->registerWorkflowStub($container, $workflowInterface);
                }
            }
        }
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
     * @param ContainerBuilder $container
     * @param ReflectionClass<T> $workflowInterface
     *
     * @return void
     */
    private function registerWorkflowStub(ContainerBuilder $container,
        ReflectionClass $workflowInterface): void
    {
        $workflow = $workflowInterface->getName();
        // The key for the options in the DI container
        $optionsId = $this->getOptionsIdInDiContainer($container, $workflowInterface);
        $definition = (new Definition($workflow))
            ->setFactory(WorkflowFactory::class . '::workflowStub')
            ->setArgument('$workflow', $workflow)
            ->setArgument('$options', new Reference($optionsId))
            ->setArgument('$workflowClient', new Reference(WorkflowClientInterface::class))
            ->setShared(false) // A new instance must be returned each time.
            ->setPublic(true); // The service facade needs the service to be public.
        $container->setDefinition($workflow, $definition);
    }

    /**
     * @template T of object
     * @param ContainerBuilder $container
     * @param ReflectionClass<T> $workflowInterface
     *
     * @return string
     */
    public function getOptionsIdInDiContainer(ContainerBuilder $container,
        ReflectionClass $workflowInterface): string
    {
        $attributes = $workflowInterface->getAttributes(WorkflowOptions::class);
        if(count($attributes) > 0)
        {
            return $attributes[0]->newInstance()->idInDiContainer;
        }

        $parameter = $container->getParameter('workflowDefaultOptions');
        return is_string($parameter) ? $parameter : 'defaultWorkflowOptions';
    }
}
