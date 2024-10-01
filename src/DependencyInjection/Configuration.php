<?php

namespace Smoq\SimsyCMS\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('simsy_cms');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('custom_blocks')
                    ->scalarPrototype()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}