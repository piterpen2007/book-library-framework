<?php

namespace EfTech\BookLibrary\Infrastructure\http;

use EfTech\BookLibrary\Infrastructure\Uri\Uri;

/**
 *  Фабрика отвечающая за создание объекта ServerRequest
 */
class ServerRequestFactory
{
    /**  Обязательные ключи в массиве входных данных
     *
     */
    private const REQUIRED_FIELDS = [
        'SERVER_PROTOCOL',
        'SERVER_PORT',
        'REQUEST_URI',
        'REQUEST_METHOD',
        'SERVER_NAME'
    ];
    private const ALLOWED_HTTP_METHOD = [
        'GET',
        'POST',
        'PUT',
        'DELETE'
    ];
    private static function httpValidateMethod(string $httpMethod): void
    {
        if (false === in_array($httpMethod, self::ALLOWED_HTTP_METHOD)) {
            throw new Exception\ErrorHttpRequestException("Некорректный http метод: '$httpMethod'");
        }
    }
    /** Валидация наличия обязательных полей. Так же проверяется что заданные поля соответствуют типу данных
     * @param array $globalServers - алидируемые данные из $_SERVER
     */
    private static function validateRequiredFields(array $globalServers): void
    {
        foreach (self::REQUIRED_FIELDS as $fieldName) {
            if (false === array_key_exists($fieldName, $globalServers)) {
                throw new Exception\ErrorHttpRequestException("Для создания объекта серверного 
                http апроса необходимо знать: '$fieldName'");
            }
            if (false === is_string($globalServers[$fieldName])) {
                throw new Exception\ErrorHttpRequestException("Для создания объекта серверного 
                http апроса необходимо чтобы поле: '$fieldName' было представляено строкой");
            }
        }
    }
    /** Создает серверный объект из глобальных переменных
     * @param array $globalServer
     * @param string|null $body
     * @return ServerRequest
     */
    public static function createFromGlobals(array $globalServer, string $body = null): ServerRequest
    {
//        self::validateRequiredFields($globalServer);
//        self::httpValidateMethod($globalServer['REQUEST_METHOD']);
//
//        $method = $globalServer['REQUEST_METHOD'];
//        $requestTarget = $globalServer['REQUEST_URI'];
//        $protocolVersion = self::extractProtocolVersion($globalServer['SERVER_PROTOCOL']);
//        $uri = Uri::createFromString(self::buildUrl($globalServer));
//        $headers = self::extractHeaders($globalServer);

        return new ServerRequest($method, $protocolVersion, $requestTarget, $uri, $headers, $body);
    }

    /** Извлекает версию протокола
     * @param string $protocolVersionRaw
     * @return string
     */
    private static function extractProtocolVersion(string $protocolVersionRaw): string
    {
        if ($protocolVersionRaw === 'HTTP/1.1') {
            $version = '1.1';
        } elseif ($protocolVersionRaw === 'HTTP/1.0') {
            $version = '1.0';
        } else {
            throw new Exception\ErrorHttpRequestException(
                "Неподдерживаемая версия http протокола: '$protocolVersionRaw'"
            );
        }
        return $version;
    }

    /** Извлекает информацию из заголовков
     * @param array $globalServer
     * @return array
     */
    private static function extractHeaders(array $globalServer): array
    {
        $headers = [];
        foreach ($globalServer as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$name] = $value;
            }
        }
        return $headers;
    }

    /** Собирает URI из $_SERVER
     * @param array $globalServer
     * @return string
     */
    private static function buildUrl(array $globalServer): string
    {
        $uri = $globalServer['REQUEST_URI'];
        if ('' !== $globalServer['SERVER_NAME']) {
            $uriServerInfo =  self::extractUriServer($globalServer) . '://' . $globalServer['SERVER_NAME'];
            self::validatePort($globalServer['SERVER_PORT']);
            $uriServerInfo .= ':' . $globalServer['SERVER_PORT'];

            if (0 === strpos($uri, '/')) {
                $uri = $uriServerInfo . $uri;
            } else {
                $uri = $uriServerInfo . '/' . $uri;
            }
        }

        return $uri;
    }

    /** Извлекает информацию о схеме
     * @param array $globalServer
     * @return array|string
     */
    private static function extractUriServer(array $globalServer): string
    {
        $schema = 'http';
        if (array_key_exists('HTTPS', $globalServer) && 'on' === $globalServer['HTTPS']) {
            $schema = 'https';
        }
        return $schema;
    }

    /** Проверка номера порта
     * @param string $portString
     */
    private static function validatePort(string $portString)
    {
        if ($portString !== (string)(int)$portString) {
            throw new Exception\ErrorHttpRequestException("Некорректный номер порта: '$portString'");
        }
    }
}
