<?php

namespace EfTech\BookLibrary\Infrastructure\http;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriInterface;
use Throwable;

class ServerResponseFactory
{
    /** Фабрика задающия psr 7 http ответы
     * @var ResponseFactoryInterface
     */
    private ResponseFactoryInterface $responseFactory;
    /** Фабрика создания объекта ответа по psr  7
     * @var StreamFactoryInterface
     */
    private StreamFactoryInterface $streamFactory;

    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        \Psr\Http\Message\StreamFactoryInterface $streamFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param int $httpCode
     * @param string $body
     * @param $headers
     * @return ResponseInterface
     */
    private function buildResponse(int $httpCode, string $body, $headers): ResponseInterface
    {
        $response = $this->responseFactory
            ->createResponse($httpCode)
            ->withBody($this->streamFactory->createStream($body));

        foreach ($headers as $headerName => $headerValue) {
            $response = $response->withHeader($headerName, $headerValue);
        }
        return $response;
    }

    /** Создаёт http ответ с данными
     * @param int $code
     * @param $data
     * @return ResponseInterface
     */
    public function createJsonResponse(int $code, $data): ResponseInterface
    {
        try {
            $body = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (Throwable $e) {
            $body = '{"status": "fail", "message": "response coding error"}';
            $code = 520;
        }

        return $this->buildResponse($code, $body, ['Content-Type' => 'application/json']);
    }

    public function createHtmlResponse(int $code, string $html): ResponseInterface
    {
        return $this->buildResponse($code, $html, ['Content-Type' => 'text/html']);
    }
    public function redirect(UriInterface $uri, int $httpCode = 302): ResponseInterface
    {
        try {
            if (!($httpCode >= 300 && $httpCode < 400)) {
                throw new RuntimeException('Некорректный код для редиректа');
            }
            $body = '';
            $headers = ['Location' => (string)$uri];
        } catch (Throwable $e) {
            $body = '<h1>Unknown Error</h1>>';
            $httpCode = 520;
            $headers = ['Content-Type' => 'text/html'];
        }
        return $this->buildResponse($httpCode, $body, $headers);
    }
}
