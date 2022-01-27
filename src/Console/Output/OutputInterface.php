<?php

namespace EfTech\BookLibrary\Infrastructure\Console\Output;

/**
 *  Интерфейс отвечающий за вывод данных в консоль
 */
interface OutputInterface
{
    /** Выводит информацию в консоли
     * @param string $text
     */
    public function print(string $text): void;
}
