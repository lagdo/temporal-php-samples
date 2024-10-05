<?php

namespace App\Temporal\Compiler;

use App\Temporal\Runtime\Runtime;
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

        // Register the classes that are tagged as activity.
        $activities = $container->findTaggedServiceIds('temporal.service.activity');
        foreach($activities as $activity => $_)
        {
            // Set the class as public, because it will need to to fetched
            // directly from the service container.
            $container->findDefinition($activity)->setPublic(true);
            $runtimeDefinition->addMethodCall('addActivity', [$activity]);
        }
    }
}
