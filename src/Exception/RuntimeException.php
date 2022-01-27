<?php

namespace EfTech\BookLibrary\Infrastructure\Exception;

/**
 * Исключение бросается в результате ошибок котоыре возникли во время выполнения
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
