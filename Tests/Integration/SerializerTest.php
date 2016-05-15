<?php
/*
* This file is part of the enum-serializer-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\EnumSerializerBundle\Tests\Integration;

use Abc\Bundle\EnumSerializerBundle\Serializer\Handler\EnumHandler;
use Abc\Bundle\EnumSerializerBundle\Tests\Fixtures\Type\TestType;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class SerializerTest extends KernelTestCase
{

    /** @var SerializerInterface */
    protected $serializer;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();
        $this->serializer = static::$kernel->getContainer()->get('jms_serializer');
    }

    /**
     * @param string $format
     * @dataProvider provideSupportedFormats
     */
    public function testSerializeToJson($format)
    {
        $subject = TestType::VALUE1();

        $data = $this->serializer->serialize($subject, $format);

        echo 'serialize:' . $data;

        $object = $this->serializer->deserialize($data, 'Abc\Bundle\EnumSerializerBundle\Tests\Fixtures\Type\TestType', $format);

        $this->assertEquals($subject, $object);
    }

    public static function provideSupportedFormats() {
        return [
            EnumHandler::getSupportedFormats()
        ];
    }
}