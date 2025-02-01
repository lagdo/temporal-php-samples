<?php

namespace App\Service\SimpleActivity;

class SimpleActivityService
{
    public function greet(string $greeting, string $name): string
    {
        return $greeting . ' ' . $name;
    }
}
