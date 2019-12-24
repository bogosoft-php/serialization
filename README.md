## bogosoft/serialization

A library of contracts and simple implementations for serializing and deserializing data.

#### Requirements

- PHP 7.1+

#### Installation

```bash
composer install bogosoft/serialization
```

#### Interfaces

|Interface Name|Description|
|--------------|-----------|
|`IDeserializer`|A strategy for deserializing data from an input stream.|
|`ISerializer`|A strategy for serializing data to an output stream.|

#### Implementations

|Implementation Name|Description|
|-------------------|-----------|
|`JsonSerializer`|A JSON serializer/deserializer.|
|`PhpSerializer`|A serializer/deserializer implementation that relies on PHP's `serialize` and `unserialize` functions.|

#### Example Usage

```php
$serializer = new \Bogosoft\Serialization\PhpSerializer();

$expected = 'Hello, World!';

$handle = fopen('php://memory', 'r+b');

$serialization = $serializer->serialize($handle, $expected);

fseek($handle, 0);

$actual = $serializer->deserialize($handle);

var_dump($actual === $expected); # outputs: bool(true)
```