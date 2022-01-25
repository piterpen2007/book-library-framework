<?php

namespace EfTech\BookLibrary\Infrastructure\http;

use EfTech\BookLibrary\Infrastructure\Uri\Uri;

/**
 *  http запрос
 */
class httpRequest extends AbstractMessage
{
    /** http метод
     * @var string
     */
    private string $method;
    /** Цель запроса
     * @var string
     */
    private string $requestTarget;
    /** Uri
     * @var Uri
     */
    private Uri $uri;


    /**
     * @param array $headers Заголовки
     * @param string $protocolVersion Версия http протокола
     * @param string|null $body Тело сообщения
     */
    public function __construct(
        string $method,
        string $protocolVersion,
        string $requestTarget,
        Uri $uri,
        array $headers,
        ?string $body
    )
    {
        parent::__construct($protocolVersion, $headers, $body);
        $this->method = $method;
        $this->requestTarget = $requestTarget;
        $this->uri = $uri;
    }

    /** возвращает тип метода
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /** возвращает цель запроса
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    /** URI
     * @return Uri
     */
    public function getUri(): Uri
    {
        return $this->uri;
    }




}