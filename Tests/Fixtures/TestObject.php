<?php
/*
* This file is part of the enum-serializer-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\EnumSerializerBundle\Tests\Fixtures;

use Abc\Bundle\EnumSerializerBundle\Tests\Fixtures\Type\TestType;
use JMS\Serializer\Annotation\Type;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class TestObject
{
    /**
     * @var TestType
     * @Type("Abc\Bundle\EnumSerializerBundle\Tests\Fixtures\Type\TestType")
     */
    protected $testType;

    /**
     * @return TestType
     */
    public function getTestType()
    {
        return $this->testType;
    }

    /**
     * @param TestType $testType
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;
    }
}