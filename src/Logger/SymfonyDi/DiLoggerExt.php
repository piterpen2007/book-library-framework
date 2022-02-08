<?php

namespace EfTech\BookLibrary\Infrastructure\Logger\SymfonyDi;

use Exception;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Расширение для Di контейнера симфони - для запуска компонента логгер
 */
class DiLoggerExt implements ExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new DiLoggerConfigurator(), $configs);
        $loader = new XmlFileLoader(
            $container,
            new FileLocator()
        );
        $loader->load(__DIR__ . '/di.xml');
        if (isset($config['fileLogger']['pathToFile'])) {
            $container->setParameter(
                'efftech.logger.fileAdapter.pathToFile',
                $config['fileLogger']['pathToFile']
            );
        }
    }

    public function getNamespace(): string
    {
        return 'https://effective-group.ru/schema/dic/eff_tech_infrastructure_logger';
    }

    public function getXsdValidationBasePath(): string
    {
        return __DIR__ . '/logger.di.config.xsd';
    }

    public function getAlias(): string
    {
        return 'e_lgr';
    }
}
