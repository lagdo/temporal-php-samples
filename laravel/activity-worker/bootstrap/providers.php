<?php

return [
    App\Providers\AppServiceProvider::class,
    Lagdo\Laravel\Facades\FacadesServiceProvider::class,
    // Temporal service providers.
    App\Providers\RuntimeServiceProvider::class,
    App\Providers\ActivityServiceProvider::class,
];
