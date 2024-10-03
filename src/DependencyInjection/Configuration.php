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
                    ->arrayPrototype()  # Allows defining multiple blocks
                        ->children()
                            ->scalarNode('class')
                                ->isRequired() # Required field
                                ->cannotBeEmpty() # Cannot be empty
                            ->end()
                            ->scalarNode('name')
                                ->isRequired() # Required field
                                ->cannotBeEmpty() # Cannot be empty
                            ->end()
                                ->scalarNode('description')
                                ->defaultNull() # Optional field
                            ->end()
                                ->scalarNode('form_class')
                                ->defaultNull() # Optional field
                            ->end()
                                ->scalarNode('template_path')
                                ->cannotBeEmpty() # Cannot be empty
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}