<?php

namespace EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 *  Параметры отвечающие за настройку di контейнера симфони
 */
final class ContainerParams
{
    /** Путь до конфига описывающий сервисы в приложение
     * @var string
     */
    private string $paths;
    /**  Параметры которые нужно передать в di container
     * @var array
     */
    private array $parameters;
    /** Коллекция расширений для di
     * @var ExtensionInterface[]
     */
    private array $extensions;

    /**
     * @param string $paths Путь до конфига описывающий сервисы в приложение
     * @param array $parameters Параметры которые нужно передать в di container
     * @param ExtensionInterface[] $extensions Коллекция расширений для di
     */
    public function __construct(string $paths, array $parameters = [], array $extensions = [])
    {
        $this->validate($extensions);
        $this->paths = $paths;
        $this->parameters = $parameters;
        $this->extensions = $extensions;
    }

    /**
     * @return string Путь до конфига описывающий сервисы в приложение
     */
    public function getPaths(): string
    {
        return $this->paths;
    }

    /**
     * @return array Параметры которые нужно передать в di container
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /** Коллекция расширений для di
     * @return ExtensionInterface[]
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /** Валидирует входящие настройки
     * @param array $extensions
     */
    private function validate(array $extensions): void
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof ExtensionInterface) {
                throw new RuntimeException('Некорректное расширение di container symfony');
            }
        }
    }
}
