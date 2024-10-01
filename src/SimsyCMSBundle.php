<?php

declare(strict_types=1);

/*
 * DatabaseDumpBundle
 * developped by Paul Le Flem <contact@paul-le-flem.fr>
 */

namespace Smoq\SimsyCMS;

use Smoq\SimsyCMS\DependencyInjection\SimsyCMSExtension;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SimsyCMSBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function getContainerExtension(): ExtensionInterface
    {
        return new SimsyCMSExtension();
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('blocks')
                    ->children()
                        ->integerNode('name')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('class')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end() // twitter
            ->end();
    }
}
