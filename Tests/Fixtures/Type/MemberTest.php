<?php
/*
* This file is part of the enum-serializer-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\EnumSerializerBundle\Tests\Fixtures\Type;

use JMS\Serializer\Annotation\Type;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class MemberTest
{
    /**
     * @var string
     * @Type("string")
     */
    public $aString;
    
    /**
     * @var string
     * @Type("Abc\Bundle\EnumSerializerBundle\Tests\Fixtures\Type\TaggedTestType")
     */
    public $enum;
}