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
 * Status of an export
 *
 * @method static TestType VALUE1()
 * @method static TestType VALUE2()
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class TestType extends Enum
{
    const VALUE1    = 'foo';
    const VALUE2    = 'bar';
}