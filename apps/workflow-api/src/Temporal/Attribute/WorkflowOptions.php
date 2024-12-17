<?php

namespace App\Temporal\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WorkflowOptions
{
    /**
     * @param string $serviceId
     */
    public function __construct(public string $serviceId)
    {}
}
