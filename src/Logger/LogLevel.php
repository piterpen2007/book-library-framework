<?php

namespace EfTech\BookLibrary\Infrastructure\Logger;

/**
 *  Уровни ошибок
 */
class LogLevel
{
    /**
     * Система не работа
     */
    public const ENERGENCY = 'energency';
    /**
     * Действия требуют безотлогательного вмешательства
     */
    public const ALERT = 'alert';
    /**
     * Критические состояния - компонент системы недоступен, неожиданное исключение
     */
    public const CRITICAL = 'critical';
    /**
     * Ошибки исполнения, не требующие сиюминутного вмешательства
     */
    public const ERROR = 'error';
    /**
     * Исключительные случаи,но не ошибки
     */
    public const WARNING = 'warning';
    /**
     * Существенные события, но это ещё не ошибки
     */
    public const NOTICE = 'notice';
    /**
     * Интересные события
     */
    public const INFO = 'info';
    /**
     *  Подробная информация для отладки
     */
    public const DEBUG = 'debug';
}