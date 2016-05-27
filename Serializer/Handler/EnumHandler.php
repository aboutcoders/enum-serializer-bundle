<?php
/*
* This file is part of the enum-serializer-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\EnumSerializerBundle\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\GraphNavigator;
use MyCLabs\Enum\Enum;

/**
 * @author hannes.schulz@aboutcoders.com
 */
class EnumHandler implements SubscribingHandlerInterface
{
    /**
     * @var array string[]
     */
    private static $types = [];

    /**
     * @return array string[]
     */
    public static function getSupportedFormats()
    {
        return ['json'];
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = array();

        foreach (['json', 'xml', 'yml'] as $format) {
            foreach (static::$types as $type) {
                $methods[] = array(
                    'type'      => $type,
                    'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                    'format'    => $format,
                    'method'    => 'deserializeEnum'
                );
            }

            foreach (static::$types as $type) {
                $methods[] = array(
                    'type'      => $type,
                    'format'    => $format,
                    'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                    'method'    => 'serializeEnum',
                );
            }
        }

        return $methods;
    }

    /**
     * @param VisitorInterface $visitor
     * @param Enum             $enum
     * @param array            $type
     * @param Context          $context
     * @return string
     */
    public function serializeEnum(VisitorInterface $visitor, Enum $enum, array $type, Context $context)
    {
        return $visitor->visitString($enum->getValue(), $type, $context);
    }

    /**
     * @param VisitorInterface           $visitor
     * @param                            $data
     * @param array                      $type
     * @return null|Enum
     */
    public function deserializeEnum(VisitorInterface $visitor, $data, array $type)
    {
        if (null === $data) {
            return null;
        }

        $class = $type['name'];

        return new $class($data);
    }

    /**
     * @param string $type The name of a class that is a subclass of MyCLabs\Enum\Enum
     * @throws \InvalidArgumentException
     */
    public static function register($type)
    {
        $ref = new \ReflectionClass($type);
        if (!$ref->isSubclassOf('MyCLabs\Enum\Enum')) {
            throw new \InvalidArgumentException('$type must refer to a class that is a subclass of MyCLabs\Enum\Enum');
        }

        if(!in_array($type, static::$types)) {
            static::$types[] = $type;
        }
    }
}