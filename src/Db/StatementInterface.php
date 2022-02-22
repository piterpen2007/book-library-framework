<?php

namespace EfTech\BookLibrary\Infrastructure\Db;

interface StatementInterface
{
    /**
     *
     * Возвращает массив, содержащий все строки полученные в результате запроса
     *
     *
     * @return array
     */
    public function fetchAll(): array;

    /**
     * Возвращает количество строк, которые были затронуты в ходе выполнения последнего запроса DELETE,
     * INSERT,UPDATE.
     *
     *
     * @return int
     */
    public function rowCount(): int;
}