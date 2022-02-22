<?php

namespace EfTech\BookLibrary\Infrastructure\Db;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

/**
 * Настройка для соединения с бд
 */
class Config
{
    /**
     *  Обязательные ключи которые обязательно должны быть для подключение к бд
     */
    private const REQUIRED_CONFIG_KEY = [
        'dbType',
        'user',
        'password',
        'dbName',
        'host',
        'port'
    ];
    /** Тип базы данных
     * @var string
     */
    private string $dbType;
    /** Пользователь бд
     * @var string
     */
    private string $user;
    /** Пароль пользователя бд
     * @var string
     */
    private string $password;

    /** Имя базы данных к которой подключаемся
     * @var string
     */
    private string $dbName;

    /** Хост для подключения к бд
     * @var string
     */
    private string $host;

    /**
     * Порт, который слушает сервер базы данных
     * @var int
     */
    private int $port;
    /**
     * Настройки для конкретного драйвера
     * @var array|null
     */
    private ?array $options = null;

    /**
     * @param string $dbType - Тип базы данных
     * @param string $user - Пользователь бд
     * @param string $password - Пароль пользователя бд
     * @param string $dbName - Имя базы данных к которой подключаемся
     * @param string $host - Хост для подключения к бд
     * @param int $port - Порт, который слушает сервер базы данных
     * @param array|null $options - Настройки для конкретного драйвера
     */
    public function __construct(
        string $dbType,
        string $user,
        string $password,
        string $dbName,
        string $host,
        int $port,
        ?array $options = null
    ) {
        $this->validate($dbType,$user,$dbName,$host);
        $this->dbType = $dbType;
        $this->user = $user;
        $this->password = $password;
        $this->dbName = $dbName;
        $this->host = $host;
        $this->port = $port;
        $this->options = $options;
    }


    /**
     * Валидация данных для подключения
     *
     * @param string $dbType
     * @param string $user
     * @param string $dbName
     * @param string $host
     * @return void
     */
    private function validate(string $dbType, string $user, string $dbName, string $host): void
    {
        $errors = [];
        if ('' === trim($dbType)) {
            $errors[] = 'Необходимо указать корректный тип сервера базы данных';
        }
        if ('' === trim($user)) {
            $errors[] = 'Необходимо указать корректное имя пользователя базы данных';
        }
        if ('' === trim($dbName)) {
            $errors[] = 'Необходимо указать корректное имя базы данных';
        }
        if ('' === trim($host)) {
            $errors[] = 'Необходимо указать корректный хост базы данных';
        }

        if (count($errors) > 0) {
            $errMsg = implode("\n", $errors);
            throw new RuntimeException($errMsg);
        }
    }

    /**
     * @return string
     */
    public function getDbType(): string
    {
        return $this->dbType;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     *  Возвращает dsn строку для подключения через PDO к базе данных
     * @return string
     */
    public function toDSN(): string
    {
        return "{$this->dbType}:host={$this->host};port={$this->port};dbname={$this->dbName}";
    }

    public static function factory(array $config): Config
    {
        $missingFields = [];
        foreach (self::REQUIRED_CONFIG_KEY as $fieldName) {
            if (false === array_key_exists($fieldName, $config)) {
                $missingFields[] = $fieldName;
            }
        }

        if (count($missingFields) > 0) {
            $errMsg = 'Для соединения с бд необходимо указать:' . implode(',', $missingFields);
            throw new RuntimeException($errMsg);
        }
        return new Config(
            $config['dbType'],
            $config['user'],
            $config['password'],
            $config['dbName'],
            $config['host'],
            $config['port'],
            array_key_exists('options', $config) ? $config['options']: []
        );
    }


}