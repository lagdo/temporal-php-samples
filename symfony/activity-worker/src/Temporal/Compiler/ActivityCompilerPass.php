<?php

namespace App\Temporal\Compiler;

use App\Temporal\Runtime\Runtime;
use Lagdo\Facades\AbstractFacade;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ActivityCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $runtimeDefinition = $container->getDefinition(Runtime::class);

        // Register the activity classes.
        /** @var array<class-string,mixed> */
        $activities = $container->findTaggedServiceIds('temporal.service.activity');
        foreach($activities as $activityClassName => $_)
        {
            $activityClass = new ReflectionClass($activityClassName);
            if(!$activityClass->isSubclassOf(AbstractFacade::class))
            {
                // Set the class as public, because it will need to be fetched
                // directly from the service container.
                $container->findDefinition($activityClassName)->setPublic(true);
                // Register the class as an activity.
                $runtimeDefinition->addMethodCall('addActivity', [$activityClassName]);
            }
        }
    }
}
