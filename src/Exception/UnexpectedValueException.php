<?php

namespace EfTech\BookLibrary\Infrastructure\Exception;

/**
 * Выбрасывается исключение если значение не совпадает с набором значений,
 * Обычно это происходит когда функция вызыввает функцию
 * и ожидает значение определённого типа
 */
class UnexpectedValueException extends \UnexpectedValueException implements ExceptionInterface
{
}
