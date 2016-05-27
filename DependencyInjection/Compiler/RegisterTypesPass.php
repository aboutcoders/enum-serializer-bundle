<?php
/*
* This file is part of the enum-serializer-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\EnumSerializerBundle\DependencyInjection\Compiler;

use Abc\Bundle\EnumSerializerBundle\Serializer\Handler\EnumHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class RegisterTypesPass implements CompilerPassInterface
{

    /**
     * @var string
     */
    private $registry;

    /**
     * @var string
     */
    private $tag;

    /**
     * Constructor.
     *
     * @param string $registry Service name of the service that registers the enum types
     * @param string $tag      The tag used for enum types
     */
    public function __construct($registry = 'abc.enum.jms_serializer.handler', $tag = 'abc.enum')
    {
        $this->registry = $registry;
        $this->tag      = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->registry) && !$container->hasAlias($this->registry)) {
            return;
        }

        // $registry = $container->findDefinition($this->registry);

        foreach ($container->findTaggedServiceIds($this->tag) as $id => $tags) {
            /**
             * Note: The class is registered here directly instead of using $registry->addMethodCall()
             * because then it is not ensured that types are set before the JMSSerializerBundle processes
             * the listeners and thus, the type would not be registered at all.
             */
            EnumHandler::register($container->getDefinition($id)->getClass());
        }
    }
}