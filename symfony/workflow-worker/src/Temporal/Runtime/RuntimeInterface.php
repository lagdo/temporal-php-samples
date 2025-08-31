<?php

namespace App\Temporal\Runtime;

interface RuntimeInterface
{
    /**
     * Start the runtime
     *
     * @return void
     */
    public function run(): void;

    /**
     * Register a workflow class
     *
     * @param class-string $workflowClassName
     *
     * @return void
     */
    public function addWorkflow(string $workflowClassName): void;
}
