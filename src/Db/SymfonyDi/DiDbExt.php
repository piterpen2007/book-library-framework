<?php

namespace EfTech\BookLibrary\Infrastructure\Db\SymfonyDi;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class DiDbExt implements ExtensionInterface
{
    private const CONNECTION_CONFIG_KEY_TO_PARAM_NAME = [
        'dbType' => 'efftech.db.connect.dbType',
        'user' => 'efftech.db.connect.user',
        'password' => 'efftech.db.connect.password',
        'dbName' => 'efftech.db.connect.dbName',
        'host' => 'efftech.db.connect.host',
        'port' => 'efftech.db.connect.port',
        'options' => 'efftech.db.connect.options',
    ];


    private const ORM_CONFIG_KEY_TO_PARAM_NAME = [
        'paths' => 'effTech.db.orm.entityPaths',
        'isDevMode' => 'effTech.db.orm.isDevMode',
        'proxyDir' => 'effTech.db.orm.proxyDir'
    ];

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new DiDbConfigurator(), $configs);
        $loader = new XmlFileLoader(
            $container,
            new FileLocator()
        );
        $loader->load(__DIR__ . '/di.xml');
        foreach (self::CONNECTION_CONFIG_KEY_TO_PARAM_NAME as $configKey => $paramName) {
            $container->setParameter($paramName, $config['connect'][$configKey]);
        }

        foreach (self::ORM_CONFIG_KEY_TO_PARAM_NAME as $configKey => $paramName) {
            $container->setParameter($paramName, $config['orm'][$configKey]);
        }
        $emDefinition = $container->getDefinition('Doctrine\Common\EventManager');

        foreach ($config['orm']['eventSubscribers'] as $subscriber) {
            $emDefinition->addMethodCall('addEventSubscriber', [new Reference($subscriber)]);
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