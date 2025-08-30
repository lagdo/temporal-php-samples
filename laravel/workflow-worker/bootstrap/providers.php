<?php

return [
    App\Providers\AppServiceProvider::class,
    // Temporal service providers.
    App\Providers\RuntimeServiceProvider::class,
    App\Providers\WorkflowServiceProvider::class,
    App\Providers\ActivityStubServiceProvider::class,
    App\Providers\ChildWorkflowServiceProvider::class,
    App\Providers\ChildWorkflowStubServiceProvider::class,
];
