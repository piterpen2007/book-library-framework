<?php

namespace EfTech\BookLibrary\Infrastructure\Console\Output;

/**
 *  Класс реализует вывод данных в буфер, Класс предназначен для тестирование консольных приложений
 */
final class BufferOutput implements OutputInterface
{
    /** Буффер для хранения результатов
     * @var array
     */
    private array $buffer = [];

    /**
     * @inheritDoc
     */
    public function print(string $text): void
    {
        $this->buffer[] = $text;
    }

    /** Возвращает буффер вывода
     * @return array
     */
    public function getBuffer(): array
    {
        return $this->buffer;
    }

}