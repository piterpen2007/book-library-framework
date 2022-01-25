<?php

namespace EfTech\BookLibrary\Infrastructure\Autoloader;
/**
 *  Автозагрузчик классов
 */
final class Autoloader
{
    /** Зарегестрированые пространства имён
     * @var array
     */
    private array $registerNamespaces = [];

    /**
     * @param array $registerNamespaces
     */
    public function __construct(array $registerNamespaces)
    {
        foreach ($registerNamespaces as $nms => $src) {
            $this->registerNamespaces[trim($nms, '\\') . '\\'] = $src;
        }
    }

    /** Получает имя файла в котором расположен заданный класс
     * @param string $className - имя загружаемого класса
     * @return string|null - путь до файла в котором расположен класс или нулл если не получилось получить имя файла
     */
    private function classNameToPath(string $className):?string
    {
        $path = null;
        foreach ($this->registerNamespaces as $prefix => $sourcePath) {
            if(strpos($className, $prefix) === 0) {
                $classNameWithoutPrefix = substr($className, strlen($prefix));

                $path = $sourcePath
                    . str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithoutPrefix)
                    . '.php';
                break;
            }
        }
        return $path;
    }
    /** Логика загрузки фалов
     * @param string $className
     * @return void
     */
    public function __invoke(string$className):void
    {
        $pathToFile = $this->classNameToPath($className);
        if (null !== $pathToFile && file_exists($pathToFile) && false === is_dir($pathToFile)) {
            require_once $pathToFile;
        }
        else {
            $b =0;
        }
    }
}

