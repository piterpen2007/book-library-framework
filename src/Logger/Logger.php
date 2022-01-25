<?php

namespace EfTech\BookLibrary\Infrastructure\Logger;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

/**
 * Универсальный логер
 */
class Logger implements LoggerInterface
{
    /**
     * Разрешённые уровни логирования
     */
    private const ALLOWED_LEVEL = [
        LogLevel::ENERGENCY => LogLevel::ENERGENCY,
        LogLevel::ALERT => LogLevel::ALERT,
        LogLevel::CRITICAL => LogLevel::CRITICAL,
        LogLevel::ERROR => LogLevel::ERROR,
        LogLevel::WARNING => LogLevel::WARNING,
        LogLevel::NOTICE => LogLevel::NOTICE,
        LogLevel::INFO => LogLevel::INFO,
        LogLevel::DEBUG => LogLevel::DEBUG,
    ];
    /** Адаптер для записи лога в конкретное хранилище
     * @var AdapterInterface
     */
    private AdapterInterface $adapter;

    /**
     * @param AdapterInterface $adapter Адаптер для записи лога в конкретное хранилище
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function energency(string $message, array $context = []): void
    {
        $this->log(LogLevel::ENERGENCY,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function alert(string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function notice(string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO,$message,$context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG,$message,$context);
    }

    /** Запись в логи сообщение
     *
     * @param string $level - уровень логирования
     * @param string $message - текст в логи
     * @param array $context - доп контекст
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void
    {
        try {
            $this->validateLevel($level);
            $formatMsg = $this->formatMsg($message,$context);
            $this->adapter->write($level, $formatMsg);
        } catch (\Throwable $e) {

        }

    }

    /** Форматирует сообщение
     * @param string $message
     * @param array $context
     * @return string
     */
    private function formatMsg(string $message, array $context):string
    {
        $date = $this->formatDate();
        $ip = $this->formatIp();
        $contextStr = $this->formatContext($context);



        return $ip . ' - ' . '[' . $date . ']' . $message . ' ' . $contextStr;
    }

    /** Валидация корректности уровня логирования
     * @param string $level
     */
    private function validateLevel(string $level):void
    {
        if(false === array_key_exists($level, self::ALLOWED_LEVEL)) {
            throw new RuntimeException(
                'Неподдерживаемый уровень логирования: ' . $level
            );
        }
    }

    /** Дата и время, когда произошло событие
     * @return string
     */
    private function formatDate():string
    {
        return (new \DateTimeImmutable())->format('d/M/Y:H:i:s O');
    }

    /** Возвращает строку с информацией о клиенте вызвавшим событие
     * @return string
     */
    private function formatIp():string
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } elseif ('cli' === PHP_SAPI) {
            $ip = 'console';
        } else {
            $ip = 'unknown';
        }
        return $ip;
    }

    private function formatContext(array $context):string
    {
        if (count($context) >0) {
            $contextStr = print_r($context,true);
        } else {
            $contextStr = '';
        }
        return $contextStr;
    }


}