<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

class ServiceLocator implements ContainerInterface
{
    /** Инстансы зарегистрированных сервисов
     * - ключ это имя сервиса(совпадает с именем класса или интерфейса)
     * - значение сам сервис(обычно объект)
     *
     * @var array
     */
    private array $instances;

    /**
     * @param array $instances
     */
    public function __construct(array $instances)
    {
        $this->instances = $instances;
    }

    /**
     * @param string $serviceName
     * @return mixed
     */
    public function get(string $serviceName)
    {
        if (false === array_key_exists($serviceName, $this->instances)) {
            throw new RuntimeException('Отсутствует сервис с именем ' . $serviceName);
        }
        return $this->instances[$serviceName];
    }
}
