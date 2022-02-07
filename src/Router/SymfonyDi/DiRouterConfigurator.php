<?php

namespace EfTech\BookLibrary\Infrastructure\Router\SymfonyDi;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 *  Структура конфига отвечающего за роутинг
 */
class DiRouterConfigurator implements \Symfony\Component\Config\Definition\ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('e_rtr');

        $treeBuilder->getRootNode()
            ->ignoreExtraKeys(false)
            ->children()




                ->arrayNode('defaultRouter')
                    ->fixXmlConfig('route', 'handlers')
                    ->children()
                        ->arrayNode('handlers')
                            ->useAttributeAsKey('pattern')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('pattern')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('value')
                                        ->isRequired()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('universalRouter')
                    ->children()
                        ->scalarNode('controllerNamespace')
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('regExpRouter')
                    ->fixXmlConfig('route', 'handlers')
                    ->children()
                        ->arrayNode('handlers')
                            ->useAttributeAsKey('pattern')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('pattern')
                                        ->isRequired()
                                    ->end()
                                    ->scalarNode('value')
                                        ->isRequired()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
