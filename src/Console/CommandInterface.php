<?php
namespace EfTech\BookLibrary\Infrastructure\Console;

/**
 * Консольная команда
 */
interface CommandInterface
{
    /** Возвращает конфиг, описывающий короткие опции команды
     * @return string
     */
    public static function getShortOption():string;
    /** Возвращает конфиг, описывающий длинные опции команды
     * @return array
     */
    public static function getLongOption():array;

    /** Запуск консольной команды
     * @param array $params - параметры консольной команды
     */
    public function __invoke(array $params):void;
}