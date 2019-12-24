<?php

declare(strict_types=1);

namespace Bogosoft\Serialization;

use Closure;

class JsonSerializer extends SerializerBase
{
    /** @var int */
    private $depth;

    /** @var int */
    private $options;

    function __construct(int $options = 0, int $depth = 512)
    {
        $this->depth   = $depth;
        $this->options = $options | JSON_THROW_ON_ERROR;
    }

    /**
     * @inheritDoc
     */
    protected function createSerialization($data): string
    {
        return json_encode($data, $this->options, $this->depth);
    }

    /**
     * @inheritDoc
     */
    protected function getDeserialization(string $serialization)
    {
        return json_decode($serialization, false, $this->depth, $this->options);
    }
}