<?php

namespace App\Temporal\Compiler;

use App\Temporal\Attribute\ActivityOptions;
use App\Temporal\Factory\ActivityFactory;
use Lagdo\Symfony\Facades\AbstractFacade;
use ReflectionClass;
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
            $reflectionClass = new ReflectionClass($activity);
            $activityInterface = $this->getInterfaceFromFacade($reflectionClass);
            if($activityInterface !== null)
            {
                // The class is a facade. Register a activity stub.
                $this->registerActivityStub($container, $activityInterface);
                // A facade doesn't need to be registered in the service container.
                $container->removeDefinition($activity);
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
        $activityInterfaceName = $serviceIdentifierMethod->invoke(null);
        $activityInterface = new ReflectionClass($activityInterfaceName);
        if(!$activityInterface ||
            count($activityInterface->getAttributes(ActivityInterface::class)) === 0)
        {
            return null;
        }

        return $activityInterface;
    }

    /**
     * @param ContainerBuilder $container
     * @param ReflectionClass $reflectionClass The key for the options in the DI container
     *
     * @return void
     */
    private function registerActivityStub(ContainerBuilder $container, ReflectionClass $reflectionClass): void
    {
        $activity = $reflectionClass->getName();
        $optionsKey = $this->getOptionsKey($container, $reflectionClass);
        $definition = (new Definition($activity))
            ->setFactory(ActivityFactory::class . '::stub')
            ->setArgument('$activity', $activity)
            ->setArgument('$options', new Reference($optionsKey))
            ->setPublic(true); // The facade needs the service to be public.
        $container->setDefinition($activity, $definition);
    }

    /**
     * @param ContainerBuilder $container
     * @param ReflectionClass $reflectionClass
     *
     * @return string
     */
    public function getOptionsKey(ContainerBuilder $container, ReflectionClass $reflectionClass): string
    {
        $attributes = $reflectionClass->getAttributes(ActivityOptions::class);

        return count($attributes) > 0 ? $attributes[0]->newInstance()->serviceId :
            $container->getParameter('activityDefaultOptions');
    }
}
