<?php

namespace EfTech\BookLibrary\Infrastructure\View;

use EfTech\BookLibrary\Infrastructure\http\httpResponse;

/** Логика отображения ответа пользователя по умолчанию
 *
 */
final class DefaultRender implements RenderInterface
{
    /**
     * @param httpResponse $httpResponse
     * @return void
     */
    public function render(httpResponse $httpResponse): void
    {
        foreach ($httpResponse->getHeaders() as $headerName => $headerValue) {
            header("$headerName: $headerValue");
        }
        http_response_code($httpResponse->getStatusCode());
        echo $httpResponse->getBody();
    }
}