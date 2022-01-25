<?php

namespace EfTech\BookLibrary\Infrastructure\Session;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

class SessionNative implements SessionInterface
{
    /**
     * Данные сессии
     *
     * @var array
     */
    private array $session;

    /**
     * @param array $session
     */
    public function __construct(array &$session)
    {
        $this->session = &$session;
    }


    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (false === $this->has($key)) {
            throw new RuntimeException('В сессии отсутствует значение для ключа:' . $key);
        }
        return $this->session[$key];
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): SessionInterface
    {
        $this->session[$key] = $value;
        return $this;
    }

    /** Создаёт сессию
     * @return SessionInterface
     */
    public static function create():SessionInterface
    {
        $sessionStatus = session_status();
        if (PHP_SESSION_DISABLED === $sessionStatus) {
            throw new RuntimeException('Сессии отключены');
        }
        if (PHP_SESSION_NONE === $sessionStatus) {
            session_start();
        }

        return new SessionNative($_SESSION);
    }
}