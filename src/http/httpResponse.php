<?php

namespace EfTech\BookLibrary\Infrastructure\http;

/**
 * http ответ
 */
class httpResponse extends AbstractMessage
{
    /** http код
     * @var int
     */
    private int $statusCode;
    /** Пояснение
     * @var string
     */
    private string $reasonPhrase;

    public function __construct(
        string $protocolVersion,
        array $headers,
        ?string $body,
        int $statusCode,
        string $reasonPhrase
    )
    {
        parent::__construct($protocolVersion, $headers, $body);
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    /** возвращает http код
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /** возвращает пояснение
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

}