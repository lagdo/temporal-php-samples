<?php

namespace App\RoadRunner\Dispatcher;

interface DispatcherInterface
{
    /**
     * @return void
     */
    public function serve(): void;
}
