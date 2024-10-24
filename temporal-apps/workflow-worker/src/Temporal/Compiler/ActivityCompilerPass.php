<?php

namespace App\Temporal\Compiler;

use App\Temporal\Attribute\ActivityOptions;
use App\Temporal\Factory\ActivityFactory;
use Lagdo\Symfony\Facades\AbstractFacade;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Temporal\Activity\ActivityInterface;

use function count;

class ActivityCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        // Process the classes that are tagged as activity.
        $activities = $container->findTaggedServiceIds('temporal.service.activity');
        foreach($activities as $activity => $_)
        {
            $activityClass = new ReflectionClass($activity);
            if(!$activityClass->isSubclassOf(AbstractFacade::class))
            {
                continue;
            }
    
            // A facade doesn't need to be registered in the service container.
            $container->removeDefinition($activity);

            if(($activityInterface = $this->getInterfaceFromFacade($activityClass)) !== null)
            {
                // The class is a facade. Register a activity stub.
                $this->registerActivityStub($container, $activityInterface);
            }
        }
    }

    /**
     * @param ReflectionClass $activityClass
     *
     * @return ReflectionClass|null
     */
    private function getInterfaceFromFacade(ReflectionClass $activityClass): ?ReflectionClass
    {
        try
        {
            // Call the protected "getServiceIdentifier()" method of the facade to get the service id.
            $serviceIdentifierMethod = $activityClass->getMethod('getServiceIdentifier');
            $serviceIdentifierMethod->setAccessible(true);
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
     * @param ContainerBuilder $container
     * @param ReflectionClass $activityInterface
     *
     * @return void
     */
    private function registerActivityStub(ContainerBuilder $container, ReflectionClass $activityInterface): void
    {
        $activity = $activityInterface->getName();
        $optionsKey = $this->getOptionsKey($container, $activityInterface);
        $definition = (new Definition($activity))
            ->setFactory(ActivityFactory::class . '::activityStub')
            ->setArgument('$activity', $activity)
            ->setArgument('$options', new Reference($optionsKey))
            ->setPublic(true); // The facade needs the service to be public.
        $container->setDefinition($activity, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @param ReflectionClass $activityInterface
     *
     * @return string
     */
    public function getOptionsKey(ContainerBuilder $container, ReflectionClass $activityInterface): string
    {
        $attributes = $activityInterface->getAttributes(ActivityOptions::class);

        return count($attributes) > 0 ? $attributes[0]->newInstance()->serviceId :
            $container->getParameter('activityDefaultOptions');
    }
}
