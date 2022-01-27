<?php

namespace EfTech\BookLibrary\Infrastructure\Logger;

/** Интерфейс логирования
 *
 */
interface LoggerInterface
{
    /**
     * @param string $message
     * @param array $context
     */
    public function energency(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function alert(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function critical(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function warning(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function notice(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function info(string $message, array $context = []): void;

    /**
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = []): void;
    /** Запись в логи сообщение
     *
     * @param string $level - уровень логирования
     * @param string $message - текст в логи
     * @param array $context - доп контекст
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void;
}
