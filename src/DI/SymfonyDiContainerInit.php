<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit\CacheParams;
use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use EfTechBookLibraryCachedContainer;

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
    /** Параметры отвечающие за кеширование
     * @var CacheParams
     */
    private CacheParams $cacheParams;

    /**
     * @param string $path
     * @param array $parameters
     * @param CacheParams|null $cacheParams
     */
    public function __construct(string $path, array $parameters = [], CacheParams $cacheParams = null)
    {
        $this->path = $path;
        $this->parameters = $parameters;
        $this->cacheParams = $cacheParams ?? new CacheParams(false);
    }

    /** Фабрика  реализующая логику создания di контайнера symfony
     *
     * @param string $path путь до xml конфига di контайнера symfony
     * @param array $parameters
     * @return ContainerBuilder|ContainerInterface
     * @throws Exception
     */
    public static function createContainerBuilder(string $path, array $parameters): ContainerBuilder
    {
        $containerBuilder = new class extends ContainerBuilder implements ContainerInterface
        {
        };
        foreach ($parameters as $parameterName => $parameterValue) {
            $containerBuilder->setParameter($parameterName, $parameterValue);
            //$containerBuilder->setParameter('kernel.project_dir', __DIR__ . '/../../../../../');
        }
        $loader = new XmlFileLoader($containerBuilder, new FileLocator());
        $loader->load($path);

        return $containerBuilder;
    }

    /** Логика создания di контейнера symfony
     * @return ContainerInterface
     * @throws Exception
     */
    public function __invoke(): ContainerInterface
    {
        if (true === $this->cacheParams->isEnableFlag()) {
            $pathToCacheFile = $this->cacheParams->getPathToCacheFile();
            $isCached = false;
            if (file_exists($pathToCacheFile)) {
                require_once $pathToCacheFile;
                $isCached = class_exists('EfTechBookLibraryCachedContainer');
            }

            if ($isCached) {
                $containerBuilder = new class extends EfTechBookLibraryCachedContainer implements
                    ContainerInterface
                {
                };
            } else {
                $containerBuilder = self::createContainerBuilder($this->path, $this->parameters);
                $containerBuilder->compile();

                $dumper = new PhpDumper($containerBuilder);
                file_put_contents(
                    $pathToCacheFile,
                    $dumper->dump(['class' => 'EfTechBookLibraryCachedContainer'])
                );
            }
        } else {
            $containerBuilder = self::createContainerBuilder($this->path, $this->parameters);
            $containerBuilder->compile();
        }

        return $containerBuilder;
    }
}
