<?php

namespace EfTech\BookLibrary\Infrastructure\Uri;

use EfTech\BookLibrary\Infrastructure\Uri\Exception\ErrorUrlException;

/**
 * Uri
 */
final class Uri
{
    /** http Схема
     * @var string
     */
    private string $schema;
    /** Доменное имя
     * @var string
     */
    private string $host;
    /** Порт
     * @var int|null
     */
    private ?int $port;
    /** Путь к ресурсу
     * @var string
     */
    private string $path;
    /** Параметры запроса
     * @var string
     */
    private string $query;
    /** Информация о пользователе
     * @var string
     */
    private string $userInfo;
    /** Фрагмент
     * @var string
     */
    private string $fragment;

    /**
     * @param string $schema http Схема
     * @param string $host Доменное имя
     * @param int|null $port Порт
     * @param string $path Путь к ресурсу
     * @param string $query Параметры запроса
     * @param string $userInfo Информация о пользователе
     * @param string $fragment Фрагмент
     */
    public function __construct(
        string $schema = '',
        string $host = '',
        ?int $port = null,
        string $path = '',
        string $query = '',
        string $userInfo =  '',
        string $fragment = ''
    ) {
        $this->schema = $schema;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->userInfo = $userInfo;
        $this->fragment = $fragment;
    }

    /** Возвращает схему http
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /** возвращает доменное имя
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /** возвращает порт
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /** возвращает путь до ресурса
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /** возвращает параметры запроса
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**  возвращает информацию о пользавателе
     * @return string
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /** возвращает фрагмент
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function __toString()
    {
        $schema = '' === $this->schema ? '' : "$this->schema://";
        $userInfo = '' === $this->userInfo ? $this->userInfo : "$this->userInfo@";
        $port = null === $this->port ? '' : ":$this->port";
        $query = '' === $this->query ? '' : "?$this->query";
        $fragment = '' === $this->fragment ? $this->fragment : "#$this->fragment";

        return "$schema$userInfo$this->host$port$this->path$query$fragment";
    }

    /** Создаёт объект URI из строки
     * @param string $uri
     * @return Uri
     */
    public static function createFromString(string $uri):Uri
    {
        $urlParts = parse_url($uri);
        if (false === is_array($urlParts)) {
            throw new ErrorUrlException("ошибка разбора строки '$uri' на составные части");
        }
        $schema = array_key_exists('scheme',$urlParts) ? $urlParts['scheme'] : '';
        $host = array_key_exists('host',$urlParts) ? $urlParts['host'] : '';
        $port = $urlParts['port'] ?? null;
        $userInfo = array_key_exists('user', $urlParts) ? $urlParts['user'] : '';
        if(array_key_exists('pass',$urlParts)) {
            $userInfo .= ":{$urlParts['pass']}";
        }
        $query = array_key_exists('query',$urlParts) ? $urlParts['query'] : '';
        $path = array_key_exists('path',$urlParts) ? $urlParts['path'] : '';
        $fragment = array_key_exists('fragment',$urlParts) ? $urlParts['fragment'] : '';

        return new Uri(
            $schema,
            $host,
            $port,
            $path,
            $query,
            $userInfo,
            $fragment
        );
    }

}