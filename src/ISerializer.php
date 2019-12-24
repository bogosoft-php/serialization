<?php

namespace Bogosoft\Serialization;

use Psr\Http\Message\StreamInterface;

/**
 * Represents a strategy for serializing data to an output stream.
 *
 * @package Bogosoft\Serialization
 */
interface ISerializer
{
    /**
     * Serialize data to a stream.
     *
     * A target SHOULD be one of:
     * - A {@see string} representing the name of a file or IO stream.
     * - A {@see resource}.
     * - A {@see StreamInterface} object.
     *
     * @param string|resource|StreamInterface $target A destination to which serialized data
     *                                                will be written.
     * @param mixed                           $data   Data to be serialized.
     */
    function serialize($target, $data): void;
}