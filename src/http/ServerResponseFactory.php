<?php

namespace EfTech\BookLibrary\Infrastructure\http;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Throwable;

class ServerResponseFactory
{
    /**
     *  Расшифровка http кодов
     */
    private const PHRASES = [
        200 => 'OK',
        201 => 'Created',
        301 => 'Temporary Redirect',
        302 => 'Found',
        400 => 'Bad request',
        404 => 'Not found',
        503 => 'Service Unavailable',
        500 => 'Internal Server Error'
    ];

    /** Создаёт http ответ с данными
     * @param int $code
     * @param $data
     * @return ResponseInterface
     */
    public static function createJsonResponse(int $code, $data): ResponseInterface
    {
        try {
            $body = json_encode($data, JSON_THROW_ON_ERROR);
            if (false === array_key_exists($code, self::PHRASES)) {
                throw new RuntimeException('Некорректный код ответа');
            }

            $phrases = '';
        } catch (Throwable $e) {
            $body = '{"status": "fail", "message": "response coding error"}';
            $code = 520;
            $phrases = 'Unknown error';
        }
        return new Response($code, ['Content-Type' => 'application/json'], $body, '1.1', $phrases);
    }

    public static function createHtmlResponse(int $code, string $html): ResponseInterface
    {
        try {
            if (false === array_key_exists($code, self::PHRASES)) {
                throw new RuntimeException('Некорректный код ответа');
            }
            $phrases = self::PHRASES[$code];
        } catch (Throwable $e) {
            $html = '<h1>Unknown Error</h1>>';
            $code = 520;
            $phrases = 'Unknown Error';
        }
        return new Response($code, ['Content-Type' => 'application/json'], $html, '1.1', $phrases);
    }
    public static function redirect(UriInterface $uri, int $httpCode = 302): ResponseInterface
    {
        try {
            if (!($httpCode >= 300 && $httpCode < 400)) {
                throw new RuntimeException('Некорректный код для редиректа');
            }
            if (false === array_key_exists($httpCode, self::PHRASES)) {
                throw new RuntimeException('Некорректный код ответа');
            }
            $phrases = self::PHRASES[$httpCode];
            $body = '';
            $headers = ['Location' => (string)$uri];
        } catch (Throwable $e) {
            $body = '<h1>Unknown Error</h1>>';
            $httpCode = 520;
            $phrases = 'Unknown Error';
            $headers = ['Content-Type' => 'text/html'];
        }
        return new Response($httpCode, $headers, $body, '1.1', $phrases);
    }
}
