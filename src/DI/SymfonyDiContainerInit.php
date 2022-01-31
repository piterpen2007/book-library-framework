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

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
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
        $containerBuilder->setParameter('kernel.project_dir', __DIR__ . '/../../../../../');
        $loader = new XmlFileLoader($containerBuilder, new FileLocator());
        $loader->load($this->path);

        $containerBuilder->compile();


        return $containerBuilder;
    }
}
