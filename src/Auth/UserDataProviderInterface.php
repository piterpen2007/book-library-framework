<?php

namespace EfTech\BookLibrary\Infrastructure\Auth;

/**
 * Поставщик данных о пользователе
 */
interface UserDataProviderInterface
{
    public function getLogin(): string;

    public function getPassword(): string;
}
