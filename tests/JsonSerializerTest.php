<?php

declare(strict_types=1);

namespace Tests;

use Bogosoft\Serialization\JsonSerializer;
use Bogosoft\Serialization\SerializerBase;

class JsonSerializerTest extends SerializerBaseTest
{
    function createDefaultSerializer(): SerializerBase
    {
        return new JsonSerializer();
    }
}