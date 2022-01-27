<?php

namespace EfTech\BookLibrary\Infrastructure\Controller;

use EfTech\BookLibrary\Infrastructure\http\httpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;

/** Интерфейс контроллера
 *
 */
interface ControllerInterface
{
    /** Обработка http запроса
     * @param ServerRequest $request
     * @return httpResponse
     */
    public function __invoke(ServerRequest $request): httpResponse;
}
