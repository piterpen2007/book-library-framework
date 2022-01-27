<?php

namespace EfTech\BookLibrary\Infrastructure\http;

/**
 *  Серверный запрос
 */
class ServerRequest extends httpRequest
{
    /** Атрибуты серверного запроса
     * @var array
     */
    private array $attributes = [];

    /**Возвращает атрибуты серверного запроса
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /** Устанавливает атрибуты
     * @param array $attributes
     * @return ServerRequest
     */
    public function setAttributes(array $attributes): ServerRequest
    {
        $this->attributes = $attributes;
        return $this;
    }




    /** Параметры запроса
     * @var array|null
     */
    private ?array $queryParams = null;

    /** Возвращает параметры запроса
     * @return array
     */
    public function getQueryParams(): array
    {
        if (null === $this->queryParams) {
            $queryParams = [];
            parse_str($this->getUri()->getQuery(), $queryParams);
            $this->queryParams = $queryParams;
        }

        return  $this->queryParams;
    }
}
