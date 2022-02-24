<?php

namespace EfTech\BookLibrary\Infrastructure\Db;

/**
 * Интерфейс для работы с соединением с базой данных
 */
interface ConnectionInterface
{
    /**
     *  Выполняет запрос
     *
     *
     * @param string $sql - sql запрос
     * @return StatementInterface
     */
    public function query(string $sql): StatementInterface;

    /**
     *  Подготовить запрос к выполнению
     *
     * @param string $sql - sql запрос
     * @return StatementInterface - результат запроса
     */
    public function prepare(string $sql): StatementInterface;
}
