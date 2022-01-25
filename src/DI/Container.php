<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use EfTech\BookLibrary\Infrastructure\Exception\DomainException;
use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use Throwable;

/**
 *
 */
final class Container implements ContainerInterface
{
    /** Уже созданные сервисы
     * @var array
     */
    private array $instances;
    /** Конфиги для создания сервисов
     * @var array
     */
    private array $services;
    /** Фабрики инкапсулирующие логику сздания сервисов
     * @var callable[]
     */
    private array $factories;

    /**
     * @param array $instances
     * @param array $services
     * @param callable[] $factories
     */
    public function __construct(array $instances = [], array $services = [], array $factories = [])
    {
        $this->instances = $instances;
        $this->services = $services;
        $this->factories = $factories;
    }

    /**
     * @inheritDoc
     */
    public function get(string $serviceName)
    {
        if (array_key_exists($serviceName,$this->instances)) {
            $service = $this->instances[$serviceName];
        } elseif (array_key_exists($serviceName,$this->services)) {
                $service = $this->createService($serviceName);
        }
        elseif (array_key_exists($serviceName,$this->factories)) {
            $service = ($this->factories[$serviceName])($this);
            $this->instances[$serviceName] = $service;
        } else {
            throw new RuntimeException('Не удалось создать сервис' . $serviceName);
        }
        return $service;
    }

    /** Создаёт контейнер из массива
     * @param array $diConfig - di конфиг
     * @return Container
     */
    public static function createFromArray(array $diConfig):Container
    {
        $instances = array_key_exists('instances',$diConfig) ? $diConfig['instances'] : [];
        $factories = array_key_exists('factories',$diConfig) ? $diConfig['factories'] : [];
        $services = array_key_exists('services',$diConfig) ? $diConfig['services'] : [];

        return new self($instances,$services, $factories);
    }

    /** Создание сервиса
     * @param string $serviceName
     * @return mixed
     */
    private function createService(string $serviceName)
    {
        $className = $serviceName;
        if (array_key_exists('class', $this->services[$serviceName])) {
            $className = $this->services[$serviceName]['class'];
        }
        if (false === is_string($className)) {
            throw new DomainException('Имя создаваемого класса должно быть стрококй');
        }
        $args = [];
        if (array_key_exists('args', $this->services[$serviceName])) {
            $args = $this->services[$serviceName]['args'];
        }
        if (false === is_array($args)) {
            throw new DomainException('Аргументы должны быть определенны массивом');
        }
        $resolvedArgs = [];
        foreach ($args as $arg) {
            $resolvedArgs[] = $this->get($arg);
        }
        try {
            $instance =  new $className(...$resolvedArgs);
        } catch (Throwable $e) {
            throw new RuntimeException('ошибка создания сервиса:' . $serviceName);
        }



        return $instance;
    }
}