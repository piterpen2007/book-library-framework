<?php

namespace EfTech\BookLibrary\Infrastructure\Db\SymfonyDi;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class DiDbExt implements ExtensionInterface
{
    private const CONFIG_KEY_TO_PARAM_NAME = [
        'dbType' => 'efftech.db.connect.dbType',
        'user' => 'efftech.db.connect.user',
        'password' => 'efftech.db.connect.password',
        'dbName' => 'efftech.db.connect.dbName',
        'host' => 'efftech.db.connect.host',
        'port' => 'efftech.db.connect.port',
        'options' => 'efftech.db.connect.options',
    ];

    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new DiDbConfigurator(), $configs);
        $loader = new XmlFileLoader(
            $container,
            new FileLocator()
        );
        $loader->load(__DIR__ . '/di.xml');
        foreach (self::CONFIG_KEY_TO_PARAM_NAME as $configKey => $paramName) {
            $container->setParameter($paramName, $config['connect'][$configKey]);
        }
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(): string
    {
        return 'https://effective-group.ru/schema/dic/eff_tech_infrastructure_db';
    }

    /**
     * @inheritDoc
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__ . '/db.di.config.xsd';
    }

    /**
     * @inheritDoc
     */
    public function getAlias(): string
    {
        return 'e_db';
    }
}