<?php

namespace EfTech\BookLibrary\Infrastructure\View;

use EfTech\BookLibrary\Infrastructure\http\httpResponse;

/** Определяет поведение классов ответственных за рендеринг результатов
 *
 */
interface RenderInterface
{
    /** Отображает результаты пользователя
     * @param httpResponse $httpResponse
     * @return void
     */
    public function render (httpResponse $httpResponse):void;
}