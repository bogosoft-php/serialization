<?php

namespace Bogosoft\Serialization;

use Bogosoft\Core\ArgumentNullException;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Throwable;

abstract class SerializerBase implements IDeserializer, ISerializer
{
    /**
     * Create a serialization from a given value.
     *
     * Implementers do not need to worry about error handling logic when
     * implementing this method. Error handling is taken care of by the
     * {@see SerializerBase::serialize()} method.
     *
     * @param  mixed  $data A value from which to create a serialization.
     * @return string       The serialized representation of the given value.
     */
    protected abstract function createSerialization($data): string;

    /**
     * @inheritDoc
     *
     * @throws DeserializationException when an error occurs during a de-serialization operation.
     * @throws ArgumentNullException when the given target is null.
     * @throws InvalidArgumentException when the given target is not, or does not represent, a stream.
     */
    function deserialize($source)
    {
        /** @var string $serialization */
        $serialization = null;

        if (null === $source)
        {
            throw new ArgumentNullException('source');
        }
        elseif (is_string($source))
        {
            $serialization = file_get_contents($source);
        }
        elseif (is_resource($source))
        {
            $serialization = stream_get_contents($source);
        }
        elseif ($source instanceof StreamInterface)
        {
            $serialization = $source->getContents();
        }
        else
        {
            throw new InvalidArgumentException('Given value does not represent a stream.');
        }

        try
        {
            return $this->getDeserialization($serialization);
        }
        catch (Throwable $t)
        {
            throw new DeserializationException('', 0, $t);
        }
    }

    /**
     * Deserialize the given {@see string} of serialized data.
     *
     * Implementers do not need to worry about error handling logic when
     * implementing this method. Error handling is taken care of by the
     * {@see SerializerBase::deserialize()} method.
     *
     * @param  string $serialization An object serialization.
     * @return mixed                 The result of deserializing the given serialization.
     */
    protected abstract function getDeserialization(string $serialization);

    /**
     * Commit a given serialization to a given file, resource or stream.
     *
     * @param resource|StreamInterface|string $target        A destination to which serialized data
     *                                                       is to be written.
     * @param string                          $serialization A string representing a serialization
     *                                                       of a value.
     *
     * @throws ArgumentNullException when the given target is null.
     * @throws InvalidArgumentException when the given target is not, or does not represent, a stream.
     */
    protected function put($target, string $serialization): void
    {
        if (is_string($target))
        {
            file_put_contents($target, $serialization);
        }
        elseif (is_resource($target))
        {
            fwrite($target, $serialization);
        }
        elseif ($target instanceof StreamInterface)
        {
            $target->write($serialization);
        }
        elseif (null === $target)
        {
            throw new ArgumentNullException('target');
        }
        else
        {
            throw new InvalidArgumentException("Given value does not represent a stream.");
        }
    }

    /**
     * @inheritDoc
     *
     * @throws SerializationException when an error occurs during a serialization operation.
     * @throws ArgumentNullException when the given target is null.
     * @throws InvalidArgumentException when the given target is not, or does not represent, a stream.
     */
    function serialize($target, $data): void
    {
        /** @var string $serialization */
        $serialization = null;

        try
        {
            $serialization = $this->createSerialization($data);
        }
        catch (Throwable $t)
        {
            throw new SerializationException('', 0, $t);
        }

        $this->put($target, $serialization);
    }
}