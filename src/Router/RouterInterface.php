<?php

namespace EfTech\BookLibrary\Infrastructure\Router;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /** Возвращает обработчик запроса
     * @param ServerRequestInterface $serverRequest - объект серверного http запроса
     * @return callable|null
     */
    public function getDispatcher(ServerRequestInterface &$serverRequest): ?callable;
}
