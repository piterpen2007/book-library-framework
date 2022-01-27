<?php

namespace EfTech\BookLibrary\Infrastructure\Session;

/**
 * Интерфейс для работы с сессиями
 */
interface SessionInterface
{
    /** Проверяет есть ли в сессии данные по заданому ключу
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
    /** Возвращает данные из сессии по заданому ключу
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
    /** Устанавливает данные в сессию
     * @param string $key
     * @param mixed $value - Значения сохранямые в сессии
     * @return $this
     */
    public function set(string $key, $value): self;
}
