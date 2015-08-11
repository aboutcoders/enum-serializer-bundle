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