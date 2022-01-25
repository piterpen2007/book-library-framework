<?php

namespace EfTech\BookLibrary\Infrastructure\Auth;

/**
 * Хранилище данных о пользователе
 */
interface UserDataStorageInterface
{
    /** Поиск пользователя по логину
     * @param string $login
     * @return UserDataProviderInterface
     */
    public function findUserByLogin(string $login):?UserDataProviderInterface;
}