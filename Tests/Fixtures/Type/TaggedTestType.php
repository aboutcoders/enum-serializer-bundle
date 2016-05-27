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

use MyCLabs\Enum\Enum;

/**
 * @method static TaggedTestType VALUE1()
 * @method static TaggedTestType VALUE2()
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class TaggedTestType extends Enum
{
    const VALUE1    = 'value1';
    const VALUE2    = 'value2';
}