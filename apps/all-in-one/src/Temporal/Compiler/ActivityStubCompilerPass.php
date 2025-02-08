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
use function is_string;

/**
 * @template I of object
 */
class ActivityStubCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        // Process the classes that are tagged as activity.
        /** @var array<class-string,mixed> */
        $activities = $container->findTaggedServiceIds('temporal.service.activity');
        foreach($activities as $activityClassName => $_)
        {
            $activityClass = new ReflectionClass($activityClassName);
            if($activityClass->isSubclassOf(AbstractFacade::class))
            {
                // A facade doesn't need to be registered in the service container.
                $container->removeDefinition($activityClassName);

                if(($activityInterface = $this->getInterfaceFromFacade($activityClass)) !== null)
                {
                    // The class is a facade on ActivityInterface. Register an activity stub.
                    $this->registerActivityStub($container, $activityInterface);
                }
            }
        }
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $activityClass
     *
     * @return ReflectionClass<I>|null
     */
    private function getInterfaceFromFacade(ReflectionClass $activityClass): ?ReflectionClass
    {
        try
        {
            // Call the protected "getServiceIdentifier()" method of the facade to get the service id.
            $serviceIdentifierMethod = $activityClass->getMethod('getServiceIdentifier');
            $serviceIdentifierMethod->setAccessible(true);
            /** @var class-string<I> */
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
     * @template T of object
     * @param ContainerBuilder $container
     * @param ReflectionClass<T> $activityInterface
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
            ->setShared(false) // A new instance must be returned each time.
            ->setPublic(true); // The facade needs the service to be public.
        $container->setDefinition($activity, $definition);
    }

    /**
     * @template T of object
     * @param ContainerBuilder $container
     * @param ReflectionClass<T> $activityInterface
     *
     * @return string
     */
    private function getOptionsKey(ContainerBuilder $container, ReflectionClass $activityInterface): string
    {
        $attributes = $activityInterface->getAttributes(ActivityOptions::class);
        if(count($attributes) > 0)
        {
            return $attributes[0]->newInstance()->serviceId;
        }

        $parameter = $container->getParameter('activityDefaultOptions');
        return is_string($parameter) ? $parameter : 'defaultActivityOptions';
    }
}
