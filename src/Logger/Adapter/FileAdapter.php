<?php

namespace EfTech\BookLibrary\Infrastructure\Logger\Adapter;

/**
 * Запись лога в файл
 */
class FileAdapter implements \EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface
{
    /**
     * @var string путь до файла где пишутся логи
     */
    private string $pathToFile;

    /**
     * @param string $pathToFile
     */
    public function __construct(string $pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
        file_put_contents($this->pathToFile, "$msg\n", FILE_APPEND);
    }
}
