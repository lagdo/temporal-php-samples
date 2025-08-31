<?php

namespace Sample\Temporal\Runtime;

interface RuntimeInterface
{
    /**
     * Start the runtime
     *
     * @return void
     */
    public function run(): void;
}
