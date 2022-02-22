<?php

namespace EfTech\BookLibrary\Infrastructure\Db;

use PDOStatement as PDOStatementNative;
use PDO;

class PDOStatement implements StatementInterface
{
    /**
     *  Результаты запроса через PDO
     * @var PDOStatementNative
     */
    private PDOStatementNative $statement;

    /**
     * @param PDOStatementNative $statement
     */
    public function __construct(PDOStatementNative $statement)
    {
        $this->statement = $statement;
    }

    public function fetchAll(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }
}