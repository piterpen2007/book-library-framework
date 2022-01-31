<?php

namespace EfTech\BookLibrary\Infrastructure\DI;

use Psr\Container\ContainerInterface as PsrContainer;

/**
 *  Интерфейс контейнеров используемых ждя внедрения зависимоостей
 */
interface ContainerInterface extends PsrContainer
{
    /**
     * @param string $serviceName
     * @return mixed
     */
    public function get(string $serviceName);
}
