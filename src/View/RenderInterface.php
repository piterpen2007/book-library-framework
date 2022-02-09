<?php

namespace EfTech\BookLibrary\Infrastructure\View;

use Psr\Http\Message\ResponseInterface;

/** Определяет поведение классов ответственных за рендеринг результатов
 *
 */
interface RenderInterface
{
    /** Отображает результаты пользователя
     * @param ResponseInterface $httpResponse $httpResponse
     * @return void
     */
    public function render(ResponseInterface $httpResponse): void;
}
