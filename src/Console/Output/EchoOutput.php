<?php

namespace EfTech\BookLibrary\Infrastructure\Console\Output;

/**
 *  Реализует вывод информации в консоль посредством использования echo
 */
final class EchoOutput implements OutputInterface
{
    /**
     * @inheritDoc
     */
    public function print(string $text): void
    {
        echo $text;
    }
}
