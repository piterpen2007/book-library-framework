<?php

namespace EfTech\BookLibrary\Infrastructure\Router\SymfonyDi;

use Exception;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 *  Расширение для di контейнера симфони - для запуска компонента, отвечающего за роутинг
 */
class DiRouterExt implements \Symfony\Component\DependencyInjection\Extension\ExtensionInterface
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new DiRouterConfigurator(), $configs);
        $loader = new XmlFileLoader(
            $container,
            new FileLocator()
        );
        $loader->load(__DIR__ . '/di.xml');
        if (isset($config['universalRouter']['controllerNamespace'])) {
            $container->setParameter(
                'efftech.router.controllerNs',
                $config['universalRouter']['controllerNamespace']
            );
        }
        if (isset($config['defaultRouter']['handlers'])) {
            $container->setParameter(
                'efftech.router.default.handlers',
                $config['defaultRouter']['handlers']
            );
        }
        if (isset($config['regExpRouter']['handlers'])) {
            $container->setParameter(
                'efftech.router.reqExp.handlers',
                $config['regExpRouter']['handlers']
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(): string
    {
        return 'https://effective-group.ru/schema/dic/eff_tech_infrastructure_router';
    }

    /**
     * @inheritDoc
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__ . '/router.di.config.xsd';
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return 'e_rtr';
    }
}
