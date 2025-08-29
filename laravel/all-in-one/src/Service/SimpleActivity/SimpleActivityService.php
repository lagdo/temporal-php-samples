<?php

namespace Sample\Service\SimpleActivity;

class SimpleActivityService
{
    public function greet(string $greeting, string $name): string
    {
        return $greeting . ' ' . $name;
    }
}
