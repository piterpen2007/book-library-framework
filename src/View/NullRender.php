<?php

namespace EfTech\BookLibrary\Infrastructure\View;

use EfTech\BookLibrary\Infrastructure\http\httpResponse;

/** Рендер заглушка
 *
 */
final class NullRender implements RenderInterface
{

    public function render(httpResponse $httpResponse): void
    {
        // TODO: Implement render() method.
    }
}