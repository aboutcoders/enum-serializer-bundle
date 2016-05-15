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

use Abc\Bundle\EnumSerializerBundle\Serializer\Handler\EnumHandler;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author hannes.schulz@aboutcoders.com
 */
class AbcEnumSerializerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        if(isset($config['serializer']['types']) && is_array($config['serializer']['types']))
        {
            foreach($config['serializer']['types'] as $enumClass)
            {
                EnumHandler::register($enumClass);
            }

            $loader->load('handler.xml');
        }
    }
} 