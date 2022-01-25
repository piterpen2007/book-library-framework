<?php

namespace EfTech\BookLibrary\Infrastructure\HttpApplication;

/**
 *  Конфиг движка сайта
 */
interface AppConfigInterface
{
    /** Возвращает флаг, который указывает что нужно скрывать сообщения о ощибках
     * @return bool
     */
    public function isHideErrorMsg(): bool;
}