<?php

namespace Tests;

use Bogosoft\Core\ArgumentNullException;
use Bogosoft\Serialization\DeserializationException;
use Bogosoft\Serialization\SerializerBase;
use GuzzleHttp\Psr7\Stream;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;

abstract class SerializerBaseTest extends TestCase
{
    abstract function createDefaultSerializer(): SerializerBase;

    function testCanRoundTripUsingFilename(): void
    {
        $filename   = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'serialization.test';
        $expected   = 'Hello, World!';
        $serializer = $this->createDefaultSerializer();

        $serializer->serialize($filename, $expected);

        $this->assertTrue(is_file($filename));

        $actual = $serializer->deserialize($filename);

        $this->assertEquals($expected, $actual);

        unlink($filename);
    }

    function testCanRoundTripUsingResource(): void
    {
        /** @var resource $handle */
        $handle     = null;
        $expected   = 'Hello, World!';
        $serializer = $this->createDefaultSerializer();

        try
        {
            $handle = fopen('php://memory', 'r+b');

            $serializer->serialize($handle, $expected);

            fseek($handle, 0);

            $actual = $serializer->deserialize($handle);

            $this->assertEquals($expected, $actual);
        }
        finally
        {
            @fclose($handle);
        }
    }

    function testCanRoundTripUsingPsrStreamInterface(): void
    {
        $expected = 'Hello, World!';
        /** @var resource $resource */
        $resource = null;
        $serializer = $this->createDefaultSerializer();
        /** @var StreamInterface $stream */
        $stream = null;

        try
        {
            $resource = fopen('php://memory', 'r+b');

            $stream = new Stream($resource);

            $serializer->serialize($stream, $expected);

            $stream->seek(0);

            $actual = $serializer->deserialize($stream);

            $this->assertEquals($expected, $actual);
        }
        finally
        {
            @$stream->close();
        }
    }

    function testThrowsArgumentNullExceptionOnDeserializeWhenSourceIsNull(): void
    {
        $serializer = $this->createDefaultSerializer();

        $this->expectException(ArgumentNullException::class);

        $serializer->deserialize(null);
    }

    function testThrowsArgumentNullExceptionOnSerializerWhenTargetIsNull(): void
    {
        $serializer = $this->createDefaultSerializer();

        $this->expectException(ArgumentNullException::class);

        $serializer->serialize(null, 'Hello, World!');
    }

    function testThrowsDeserializationExceptionWhenSourceDoesNotContainValidSerializedData(): void
    {
        /** @var resource $handle */
        $handle = null;

        try
        {
            $handle = fopen('php://memory', 'r+b');

            fwrite($handle, "\0");

            fseek($handle, 0);

            $this->expectException(DeserializationException::class);

            $this->createDefaultSerializer()->deserialize($handle);
        }
        finally
        {
            @fclose($handle);
        }
    }

    function testThrowsInvalidArgumentExceptionOnDeserializeWhenSourceDoesNotRepresentAStream(): void
    {
        $serializer = $this->createDefaultSerializer();

        $this->expectException(InvalidArgumentException::class);

        $serializer->deserialize(true);
    }

    function testThrowsInvalidArgumentExceptionOnSerializeWhenTargetDoesNotRepresentAStream(): void
    {
        $serializer = $this->createDefaultSerializer();

        $this->expectException(InvalidArgumentException::class);

        $serializer->serialize(true, 'Hello, World!');
    }
}