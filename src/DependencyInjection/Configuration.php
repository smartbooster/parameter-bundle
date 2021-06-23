<?php

namespace Smart\ParameterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Mathieu Ducrot <mathieu.ducrot@smartbooster.io>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('smart_parameter');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->append($this->getParametersNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function getParametersNode(): NodeDefinition
    {
        return (new TreeBuilder('parameters'))->getRootNode()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('value')->isRequired()->end()
                    ->scalarNode('help')->end()
                ->end()
            ->end()
        ;
    }
}
