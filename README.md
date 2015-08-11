Symfony EnumSerializerBundle
==========================

A symfony bundle to serialize/deserialize enumerations of type myclabs/php-enum with jms/serializer.

## Installation

Add the bundle:

``` json
{
    "require": {
        "aboutcoders/enum-serializer-bundle": "dev-master"
    }
}
```

Enable the bundles in the kernel:

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

* Add unit tests
* Add support for other data types such as XML