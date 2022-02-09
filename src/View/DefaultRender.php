<?php

namespace EfTech\BookLibrary\Infrastructure\View;

use Psr\Http\Message\ResponseInterface;

/** Логика отображения ответа пользователя по умолчанию
 *
 */
final class DefaultRender implements RenderInterface
{
    /**
     * @param ResponseInterface $httpResponse $httpResponse
     * @return void
     */
    public function render(ResponseInterface $httpResponse): void
    {
        foreach ($httpResponse->getHeaders() as $headerName => $headerValue) {
            header("$headerName: {$httpResponse->getHeaderLine($headerName)}}");
        }
        http_response_code($httpResponse->getStatusCode());
        echo $httpResponse->getBody();
    }
}
