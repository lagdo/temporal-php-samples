<?php

namespace Sample\Temporal\Runtime;

use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Temporal\Worker\WorkerFactoryInterface;
use Temporal\Worker\WorkerInterface;

class Runtime implements RuntimeInterface
{
    /**
     * Activity class names
     *
     * @var array<class-string>
     */
    private array $activities = [];

    public function __construct(private ContainerInterface $container,
        private WorkerInterface $worker, private WorkerFactoryInterface $workerFactory)
    {}

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        // Register the activities
        foreach($this->activities as $activity)
        {
            $this->worker->registerActivity($activity, function(ReflectionClass $class) {
                return $this->container->get($class->getName());
            });
        }

        $this->workerFactory->run();
    }

    /**
     * @inheritDoc
     */
    public function addActivity(string $activity): void
    {
        $this->activities[] = $activity;
    }
}
