<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 *  Компонент инициализирующий di контайнер symfony
 */
class SymfonyDiContainerInit
{
    /** путь до конфина описывающий сервисы приложения
     * @var string
     */
    private string $path;
    private array $parameters;

    /**
     * @param string $path
     * @param array $parameters
     */
    public function __construct(string $path, array $parameters = [])
    {
        $this->path = $path;
        $this->parameters = $parameters;
    }

    /** Логика создания di контейнера symfony
     * @return ContainerInterface
     * @throws Exception
     */
    public function __invoke(): ContainerInterface
    {
        $containerBuilder = new class extends ContainerBuilder implements ContainerInterface
        {
        };
        foreach ($this->parameters as $parameterName => $parameterValue) {
            $containerBuilder->setParameter('kernel.project_dir', __DIR__ . '/../../../../../');
        }
        $loader = new XmlFileLoader($containerBuilder, new FileLocator());
        $loader->load($this->path);

        $containerBuilder->compile();


        return $containerBuilder;
    }
}
