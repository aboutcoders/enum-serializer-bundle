<?php
/*
* This file is part of the enum-serializer-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\EnumSerializerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author hannes.schulz@aboutcoders.com
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('abc_enum_serializer')
            ->children()
                ->arrayNode('serializer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('types')
                            ->canBeUnset()
                            ->useAttributeAsKey('name')
                            ->prototype('scalar')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
