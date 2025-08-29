<?php

namespace App\Temporal\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ActivityOptions
{
    /**
     * @param string $idInDiContainer
     */
    public function __construct(public string $idInDiContainer)
    {}
}
