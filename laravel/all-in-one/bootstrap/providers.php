<?php

return [
    App\Providers\AppServiceProvider::class,
    // Temporal service providers.
    App\Providers\RuntimeServiceProvider::class,
    App\Providers\OptionsServiceProvider::class,
    App\Providers\WorkflowServiceProvider::class,
    App\Providers\WorkflowStubServiceProvider::class,
    App\Providers\ChildWorkflowServiceProvider::class,
    App\Providers\ChildWorkflowStubServiceProvider::class,
    App\Providers\ActivityServiceProvider::class,
    App\Providers\ActivityStubServiceProvider::class,
];
