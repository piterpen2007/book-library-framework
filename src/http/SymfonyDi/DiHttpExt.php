<?php

namespace EfTech\BookLibrary\Infrastructure\http\SymfonyDi;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Расширение di container symfony для запуска компонента http
 */
class DiHttpExt implements ExtensionInterface
{

    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator()
        );
        $loader->load(__DIR__ . '/di.xml');
    }

    public function getNamespace(): string
    {
        return 'https://effective-group.ru/schema/dic/eff_tech_infrastructure_http';
    }

    public function getXsdValidationBasePath(): bool
    {
        return false;
    }

    public function getAlias(): string
    {
        return 'e_http';
    }
}