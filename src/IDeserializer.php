<?php

namespace Bogosoft\Serialization;

use Psr\Http\Message\StreamInterface;

/**
 * Represents a strategy for de-serializing data from a source stream.
 *
 * @package Bogosoft\Serialization
 */
interface IDeserializer
{
    /**
     * Deserialize data from a source stream.
     *
     * Implementations SHOULD throw a {@see DeserializationException} if errors are
     * encountered during a de-serialization operation.
     *
     * A source SHOULD be one of:
     * - A {@see string} representing the name of a file or IO stream.
     * - A {@see resource}.
     * - A {@see StreamInterface} object.
     *
     * @param  string|resource|StreamInterface $source A source of serialized data.
     * @return mixed|null                              The result of de-serializing the
     *                                                 given source.
     */
    function deserialize($source);
}