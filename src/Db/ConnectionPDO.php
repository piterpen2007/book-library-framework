<?php

namespace EfTech\BookLibrary\Infrastructure\Db;

use PDO;

class ConnectionPDO implements ConnectionInterface
{
    /** Конфиг подключения
     * @var Config
     */
    private Config $config;
    /**
     * PDO
     * @var PDO|null
     */
    private ?PDO $pdo = null;

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        if (null === $this->pdo) {
            $this->pdo = new PDO(
                $this->config->toDSN(),
                $this->config->getUser(),
                $this->config->getPassword(),
                $this->config->getOptions()
            );
        }
        return $this->pdo;
    }


    /**
     * @param Config $config - Конфиг подключения
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function query(string $sql): StatementInterface
    {
        $statement = $this->getPdo()->query($sql);

        return new PDOStatement($statement);
    }

    /**
     * @param string $sql
     * @return StatementInterface
     */
    public function prepare(string $sql): StatementInterface
    {
        $statementPdo = $this->getPdo()->prepare($sql);

        return new PDOStatement($statementPdo);
    }


    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->getPdo()->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->getPdo()->commit();
    }

    /**
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->getPdo()->rollBack();
    }
}
