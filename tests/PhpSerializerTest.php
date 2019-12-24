<?php

declare(strict_types=1);

namespace Tests;

use __PHP_Incomplete_Class;
use Bogosoft\Serialization\PhpSerializer;
use Bogosoft\Serialization\SerializationException;
use Bogosoft\Serialization\SerializerBase;

class PhpSerializerTest extends SerializerBaseTest
{
    function createDefaultSerializer(): SerializerBase
    {
        return new PhpSerializer();
    }

    function testObjectOfNonWhitelistedClassIsDeserializedToIncompleteClassWhenSerializerIsConstrained() : void
    {
        $person     = new Person('Percy');
        $handle     = null;
        $serializer = new PhpSerializer([], true);

        try
        {
            $handle = fopen('php://memory', 'r+b');

            $serializer->serialize($handle, $person);

            fseek($handle, 0);

            $actual = $serializer->deserialize($handle);

            $this->assertInstanceOf(__PHP_Incomplete_Class::class, $actual);
        }
        finally
        {
            @fclose($handle);
        }
    }

    function testObjectOfWhitelistedClassIsFullyDeserializedWhenSerializerIsConstrained(): void
    {
        $person     = new Person('Percy');
        $handle     = null;
        $serializer = new PhpSerializer([Person::class], true);

        try
        {
            $handle = fopen('php://memory', 'r+b');

            $serializer->serialize($handle, $person);

            fseek($handle, 0);

            $actual = $serializer->deserialize($handle);

            $this->assertInstanceOf(Person::class, $actual);
        }
        finally
        {
            @fclose($handle);
        }
    }

    function testThrowsSerializationExceptionWhenAttemptingToSerializeInvalidObject(): void
    {
        $serializer = $this->createDefaultSerializer();

        $this->expectException(SerializationException::class);

        $serializer->serialize('php://memory', function(): void {});
    }
}