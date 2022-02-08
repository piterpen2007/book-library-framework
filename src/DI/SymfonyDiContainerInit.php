<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit\CacheParams;
use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use EfTech\BookLibrary\Infrastructure\Router\SymfonyDi\DiRouterExt;
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

    private ContainerParams $containerParams;

    /**
     * @param ContainerParams $containerParams
     * @param CacheParams|null $cacheParams
     */
    public function __construct(ContainerParams $containerParams, CacheParams $cacheParams = null)
    {
        $this->containerParams = $containerParams;
        $this->cacheParams = $cacheParams ?? new CacheParams(false);
    }

    /** Фабрика  реализующая логику создания di контайнера symfony
     *
     * @param ContainerParams $containerParams
     * @return ContainerBuilder|ContainerInterface
     * @throws Exception
     */
    public static function createContainerBuilder(ContainerParams $containerParams): ContainerBuilder
    {
        $containerBuilder = new class extends ContainerBuilder implements ContainerInterface
        {
        };
        //$containerBuilder->registerExtension(new DiRouterExt());

        foreach ($containerParams->getExtensions() as $extension) {
            $containerBuilder->registerExtension($extension);
        }

        foreach ($containerParams->getParameters() as $parameterName => $parameterValue) {
            $containerBuilder->setParameter($parameterName, $parameterValue);
        }

        $loader = new XmlFileLoader($containerBuilder, new FileLocator());
        $loader->load($containerParams->getPaths());

        $xmlConfig = glob(dirname($containerParams->getPaths()) . '/*.di.config.xml');
        foreach ($xmlConfig as $pathToExtensionConfig) {
            $loader->load($pathToExtensionConfig);
        }
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
                $containerBuilder = self::createContainerBuilder(
                    $this->containerParams
                );
                $containerBuilder->compile();

                $dumper = new PhpDumper($containerBuilder);
                file_put_contents(
                    $pathToCacheFile,
                    $dumper->dump(['class' => 'EfTechBookLibraryCachedContainer'])
                );
            }
        } else {
            $containerBuilder = self::createContainerBuilder(
                $this->containerParams
            );
            $containerBuilder->compile();
        }

        return $containerBuilder;
    }
}
