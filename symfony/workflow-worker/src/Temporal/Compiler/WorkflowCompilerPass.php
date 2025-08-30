<?php

namespace App\Temporal\Compiler;

use App\Temporal\Runtime\Runtime;
use Lagdo\Facades\AbstractFacade;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

        // Register the workflow classes.
        /** @var array<class-string,mixed> */
        $workflows = $container->findTaggedServiceIds('temporal.service.workflow');
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
