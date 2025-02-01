<?php

namespace RoadRunner\Dispatcher;

interface DispatcherInterface
{
    /**
     * @return void
     */
    public function serve(): void;
}
