<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

/**
 * Манаджер сервисов
 */
class ServiceManager implements ContainerInterface
{
    /** Инстансы зарегистрированных сервисов
     * - ключ это имя сервиса(совпадает с именем класса или интерфейса)
     * - значение сам сервис(обычно объект)
     *
     * @var array
     */
    private array $instances;
    /**
     * @var callable[]
     */
    private array $factories;

    /**
     * @param array $instances
     * @param array $factories
     */
    public function __construct(array $instances = [], array $factories = [])
    {
        $this->instances = $instances;
        $this->registerFactories(...$factories);
    }

    private function registerFactories(callable ...$factories): void
    {
        $this->factories = $factories;
    }


    /**
     * @inheritDoc
     */
    public function get(string $serviceName)
    {
        if (array_key_exists($serviceName, $this->instances)) {
            $service = $this->instances[$serviceName];
        } elseif (array_key_exists($serviceName, $this->factories)) {
            $service = ($this->factories[$serviceName])($this);
            $this->instances[$serviceName] = $service;
        } else {
            throw new RuntimeException('Не удалось создать сервис' . $serviceName);
        }
        return $service;
    }
}
