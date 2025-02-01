<?php

namespace App\Temporal\Runtime;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Temporal\Worker\WorkerFactoryInterface;
use Temporal\Worker\WorkerInterface;

use function count;

class Runtime implements RuntimeInterface
{
    /**
     * Workflow class names
     *
     * @var array<class-string>
     */
    private array $workflows = [];

    /**
     * Activity class names
     *
     * @var array<class-string>
     */
    private array $activities = [];

    public function __construct(private ContainerInterface $container, private WorkerInterface $worker,
        private WorkerFactoryInterface $workerFactory)
    {}

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        // Register the workflow classes
        if(count($this->workflows) > 0)
        {
            $this->worker->registerWorkflowTypes(...$this->workflows);
        }

        $this->workerFactory->run();
    }

    /**
     * @inheritDoc
     */
    public function addWorkflow(string $workflow): void
    {
        $this->workflows[] = $workflow;
    }
}
