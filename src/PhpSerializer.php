<?php

declare(strict_types=1);

namespace Bogosoft\Serialization;

/**
 * An implementation of the {@see ISerializer} contract that utilizes the built-in
 * serialization functionality of PHP.
 *
 * @package Bogosoft\Serialization
 */
class PhpSerializer extends SerializerBase
{
    /** @var array|bool */
    private $options;

    /**
     * Create a new PHP serializer.
     *
     * @param array $whitelist   An array of class names that can be fully de-serialized. If the new
     *                           serializer is constrained to use only classes from this list, an
     *                           attempt to deserialize an object of a non-whitelisted class will
     *                           instead result in a {@see \__PHP_Incomplete_Class}.
     * @param bool  $constrained A value indicating whether or not to abide by the given whitelist.
     */
    function __construct(array $whitelist = [], bool $constrained = false)
    {
        $this->options = ['allowed_classes'];

        if ($constrained)
        {
            $this->options['allowed_classes'] = count($whitelist) > 0 ? $whitelist : false;
        }
        else
        {
            $this->options['allowed_classes'] = true;
        }
    }

    /**
     * @inheritDoc
     */
    protected function createSerialization($data): string
    {
        return serialize($data);
    }

    /**
     * @inheritDoc
     */
    protected function getDeserialization(string $serialization)
    {
        return unserialize($serialization, $this->options);
    }
}