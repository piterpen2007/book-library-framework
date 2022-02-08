<?php

namespace EfTech\BookLibrary\Infrastructure\Logger\SymfonyDi;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 *  Структура конфига компонента отвечающего за описание сервисов компонента Logger
 */
class DiLoggerConfigurator implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('e_lgr');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('fileLogger')
                    ->children()
                        ->scalarNode('pathToFile')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
