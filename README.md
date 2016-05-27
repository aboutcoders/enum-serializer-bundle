AbcEnumSerializerBundle
=======================

A symfony bundle to serialize/deserialize enumerations of type [myclabs/php-enum](https://github.com/myclabs/php-enum) with [jms/serializer](https://github.com/schmittjoh/serializer).

Build Status: [![Build Status](https://travis-ci.org/aboutcoders/enum-serializer-bundle.svg?branch=master)](https://travis-ci.org/aboutcoders/enum-serializer-bundle)

**Note: At this point `json` is the only supported format.**

## Installation

Add the AbcEnumSerializerBundle to your `composer.json` file:

```
php composer.phar require aboutcoders/enum-serializer-bundle
```

Include the bundle in the AppKernel.php class:

``` php
# app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        ...
        new Abc\Bundle\EnumSerializerBundle\AbcEnumSerializerBundle(),
    );
}
```

## Usage

Define the enum as defined by [myclabs/php-enum](https://github.com/myclabs/php-enum):

``` php
namespace Acme;

use MyCLabs\Enum\Enum;

class MyEnum
{
    const VALUE_1;
    const VALUE_2;
}
```

In order to serialize/deserialize the enumeration you can register the type in app/config.yml

``` yaml
# app/config/config.yml
abc_enum_serializer:
    serializer:
        types:
            - Acme\MyEnum
```

or you can register the enum within the service container and tag it with with the tag `abc.enum`

``` yaml
services:
    my_enum:
        class: Acme\MyEnum
        tags:
            - { name: abc.enum }
```

**Note, this service should be declared private and should never be instantiated, since this would lead to an exception. The only purpose of this service is to provide the fully qualified name of the class that needs to be registered as enum type.**

Finally you can configure the type in case you use it as a member variable

``` php
use JMS\Serializer\Annotation\Type;

class MyExample
{

    /**
     * @Type("Acme\MyEnum")
     */
    private $myEnum;

}
```

or serialize/deserialize it directly referencing the type:

``` php

$serializer = $container->get('jms_serializer');

$data = $serializer->serialize($subject, 'json');

$enum = $this->serializer->deserialize($data, MyExample::class, 'json');

```

## ToDo
* Add support for the formats XML and YAML
* Add support for Symfony 2.3