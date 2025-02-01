<?php

namespace Boot\Dispatcher;

interface DispatcherInterface
{
    /**
     * @return void
     */
    public function serve(): void;
}
