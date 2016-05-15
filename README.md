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

        new Abc\Bundle\EnumSerializerBundle\AbcEnumSerializerBundle(),
    );
}
```
## Usage

In order to serialize/deserialize enumerations you have to register the type in app/config.yml

``` yaml
# app/config/config.yml
abc_enum_serializer:
    serializer:
        types:
            - My\EnumType
```

Last step is to configure the type in the annotation at the places where it is used:

``` php

use JMS\Serializer\Annotation\Type;

class MyExample
{

    /**
     * @Type("My\EnumType")
     */
    private $permission;

}
```

## ToDo
* Add support for the formats XML and YAML
* Add support for Symfony 2.3