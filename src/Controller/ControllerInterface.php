<?php

namespace EfTech\BookLibrary\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/** Интерфейс контроллера
 *
 */
interface ControllerInterface
{
    /** Обработка http запроса
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface;
}
