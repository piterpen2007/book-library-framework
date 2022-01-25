<?php

namespace EfTech\BookLibrary\Infrastructure\http;

/**
 *  Абстрактное http сообщение
 */
class AbstractMessage
{
    /** Версия http протокола
     * @var string
     */
    private string $protocolVersion;
    /** Заголовки
     * @var array
     */
    private array $headers;
    /** Тело сообщения
     * @var string|null
     */
    private ?string $body;

    /**
     * @param string $protocolVersion Версия http протокола
     * @param array $headers Заголовки
     * @param string|null $body Тело сообщения
     */
    public function __construct(string $protocolVersion, array $headers, ?string $body)
    {
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
        $this->body = $body;
    }

    /** Возвращает версию http протокола
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /** возвращает заголовки
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /** возвращает тело сообщения
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }


}