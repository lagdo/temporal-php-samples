<?php

namespace Sample\Temporal\Runtime;

use Temporal\Worker\WorkerFactoryInterface;

class Runtime implements RuntimeInterface
{
    /**
     * The constructor
     *
     * @param WorkerFactoryInterface $workerFactory
     */
    public function __construct(private WorkerFactoryInterface $workerFactory)
    {}

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        $this->workerFactory->run();
    }
}
