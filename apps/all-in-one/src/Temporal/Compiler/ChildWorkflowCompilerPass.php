<?php

namespace App\Temporal\Compiler;

use App\Temporal\Runtime\Runtime;
use Lagdo\Symfony\Facades\AbstractFacade;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ChildWorkflowCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $runtimeDefinition = $container->getDefinition(Runtime::class);

        // Register the classes that are tagged as child workflow.
        /** @var array<class-string,mixed> */
        $workflows = $container->findTaggedServiceIds('temporal.service.workflow.child');
        foreach($workflows as $workflowClassName => $_)
        {
            $workflowClass = new ReflectionClass($workflowClassName);
            if(!$workflowClass->isSubclassOf(AbstractFacade::class))
            {
                // Register the class as a workflow.
                $runtimeDefinition->addMethodCall('addWorkflow', [$workflowClassName]);
            }
        }
    }
}
