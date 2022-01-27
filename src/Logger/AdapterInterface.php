<?php

namespace EfTech\BookLibrary\Infrastructure\Logger;

/**
 * Адаптер для записи логов
 */
interface AdapterInterface
{
    /** Записать лог
     * @param string $logLevel - уровень логируемого сообщения
     * @param string $msg - сообщение для записи в лог
     */
    public function write(string $logLevel, string $msg): void;
}
