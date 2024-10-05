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
     * Register an activity class
     *
     * @param string $activityClassName
     *
     * @return void
     */
    public function addActivity(string $activityClassName): void;
}
