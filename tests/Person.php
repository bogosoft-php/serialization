<?php

declare(strict_types=1);

namespace Tests;

class Person
{
    /** @var string */
    private $name;

    function __construct(string $name)
    {
        $this->name = $name;
    }

    function getName(): string
    {
        return $this->name;
    }
}