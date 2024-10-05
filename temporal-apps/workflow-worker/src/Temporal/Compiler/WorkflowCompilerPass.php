<?php

namespace App\Temporal\Compiler;

use App\Temporal\Attribute\ChildWorkflowOptions;
use App\Temporal\Factory\WorkflowFactory;
use App\Temporal\Runtime\Runtime;
use Lagdo\Symfony\Facades\AbstractFacade;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Temporal\Workflow\WorkflowInterface;

class WorkflowCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $runtimeDefinition = $container->getDefinition(Runtime::class);

        // Register the classes that are tagged as workflow.
        $workflows = $container->findTaggedServiceIds('temporal.service.workflow');
        foreach($workflows as $workflow => $_)
        {
            $reflectionClass = new ReflectionClass($workflow);
            $workflowInterface = $this->getInterfaceFromFacade($reflectionClass);
            if($workflowInterface === null)
            {
                $runtimeDefinition->addMethodCall('addWorkflow', [$workflow]);
            }
            else
            {
                // The class is a facade. Register a child workflow stub.
                $this->registerChildWorkflowStub($container, $workflowInterface);
                // A facade doesn't need to be registered in the service container.
                $container->removeDefinition($workflow);
            }
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return ReflectionClass|null
     */
    private function getInterfaceFromFacade(ReflectionClass $reflectionClass): ?ReflectionClass
    {
        if(!$reflectionClass->isSubclassOf(AbstractFacade::class))
        {
            return null;
        }

        $serviceIdentifierMethod = $reflectionClass->getMethod('getServiceIdentifier');
        $serviceIdentifierMethod->setAccessible(true);
        $workflowInterfaceName = $serviceIdentifierMethod->invoke(null);
        $workflowInterface = new ReflectionClass($workflowInterfaceName);
        if(!$workflowInterface ||
            count($workflowInterface->getAttributes(WorkflowInterface::class)) === 0)
        {
            return null;
        }

        return $workflowInterface;
    }

    /**
     * @param ContainerBuilder $container
     * @param ReflectionClass $reflectionClass The key for the options in the DI container
     *
     * @return void
     */
    private function registerChildWorkflowStub(ContainerBuilder $container, ReflectionClass $reflectionClass): void
    {
        $workflow = $reflectionClass->getName();
        $optionsKey = $this->getOptionsKey($container, $reflectionClass);
        $definition = (new Definition($workflow))
            ->setFactory(WorkflowFactory::class . '::childStub')
            ->setArgument('$workflow', $workflow)
            ->setArgument('$options', new Reference($optionsKey))
            ->setPublic(true); // The facade needs the service to be public.
        $container->setDefinition($workflow, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @param ReflectionClass $reflectionClass
     *
     * @return string
     */
    public function getOptionsKey(ContainerBuilder $container, ReflectionClass $reflectionClass): string
    {
        $attributes = $reflectionClass->getAttributes(ChildWorkflowOptions::class);

        return count($attributes) > 0 ? $attributes[0]->newInstance()->serviceId :
            $container->getParameter('childWorkflowDefaultOptions');
    }
}
