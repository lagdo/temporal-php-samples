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
            $workflowClass = new ReflectionClass($workflow);
            if(!$workflowClass->isSubclassOf(AbstractFacade::class))
            {
                $runtimeDefinition->addMethodCall('addWorkflow', [$workflow]);
                continue;
            }
            $workflowInterface = $this->getInterfaceFromFacade($workflowClass);
            if($workflowInterface !== null)
            {
                // The class is a facade. Register a child workflow stub.
                $this->registerChildWorkflowStub($container, $workflowInterface);
                // A facade doesn't need to be registered in the service container.
                $container->removeDefinition($workflow);
            }
        }
    }

    /**
     * @param ReflectionClass $workflowClass
     *
     * @return ReflectionClass|null
     */
    private function getInterfaceFromFacade(ReflectionClass $workflowClass): ?ReflectionClass
    {
        if(!$workflowClass->isSubclassOf(AbstractFacade::class))
        {
            return null;
        }

        // Call the protected "getServiceIdentifier()" method of the facade to get the service id.
        $serviceIdentifierMethod = $workflowClass->getMethod('getServiceIdentifier');
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
     * @param ReflectionClass $workflowInterface
     *
     * @return void
     */
    private function registerChildWorkflowStub(ContainerBuilder $container, ReflectionClass $workflowInterface): void
    {
        $workflow = $workflowInterface->getName();
        $optionsKey = $this->getOptionsKey($container, $workflowInterface);
        $definition = (new Definition($workflow))
            ->setFactory(WorkflowFactory::class . '::childWorkflowStub')
            ->setArgument('$workflow', $workflow)
            ->setArgument('$options', new Reference($optionsKey))
            ->setPublic(true); // The facade needs the service to be public.
        $container->setDefinition($workflow, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @param ReflectionClass $workflowInterface
     *
     * @return string
     */
    public function getOptionsKey(ContainerBuilder $container, ReflectionClass $workflowInterface): string
    {
        $attributes = $workflowInterface->getAttributes(ChildWorkflowOptions::class);

        return count($attributes) > 0 ? $attributes[0]->newInstance()->serviceId :
            $container->getParameter('childWorkflowDefaultOptions');
    }
}
