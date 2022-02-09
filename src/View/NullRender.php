<?php

namespace EfTech\BookLibrary\Infrastructure\View;

use Psr\Http\Message\ResponseInterface;

/** Рендер заглушка
 *
 */
final class NullRender implements RenderInterface
{
    public function render(ResponseInterface $httpResponse): void
    {
        // TODO: Implement render() method.
    }
}
