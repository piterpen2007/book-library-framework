<?php

namespace EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

/**
 *  Параметры отвечающие за кеширование di контайнера
 */
class CacheParams
{
    /** Флаг определяющий нужно ли кешировать di контайнер
     *
     *  true - включено
     *  false - выключено
     * @var bool
     */
    private bool $enableFlag;

    /** Путь до файла с кешём
     * @var string|null
     */
    private ?string $pathToCacheFile;

    /**
     * @param bool $enableFlag - Флаг определяющий нужно ли кешировать di контайнер
     * @param string|null $pathToCacheFile - Путь до файла с кешём
     */
    public function __construct(bool $enableFlag, ?string $pathToCacheFile = null)
    {
        $this->enableFlag = $enableFlag;
        $this->pathToCacheFile = $pathToCacheFile;
        $this->validate();
    }

    /** Флаг определяющий нужно ли кешировать di контайнер
     *
     *  true - включено
     *  false - выключено
     *
     * @return bool
     */
    public function isEnableFlag(): bool
    {
        return $this->enableFlag;
    }

    /** Путь до файла с кешём
     *
     * @return string|null
     */
    public function getPathToCacheFile(): ?string
    {
        return $this->pathToCacheFile;
    }
    /**
     *  Логика валидации параметров кеширования
     */
    private function validate(): void
    {
        if (false === $this->enableFlag) {
            return;
        }
        if (null === $this->pathToCacheFile || '' === trim($this->pathToCacheFile)) {
            $err = 'Некорректный путь до файла с скомпилированным di container';
            throw new RuntimeException($err);
        }
    }
}
