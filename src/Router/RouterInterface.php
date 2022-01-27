<?php

namespace EfTech\BookLibrary\Infrastructure\Router;

use EfTech\BookLibrary\Infrastructure\http\ServerRequest;

interface RouterInterface
{
    /** Возвращает обработчик запроса
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return callable|null
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable;
}
