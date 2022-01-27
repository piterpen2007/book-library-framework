<?php

namespace EfTech\BookLibrary\Infrastructure\Logger\Adapter;

/**
 * Адпатер пишет логи в никуда
 */
class NullAdapter implements \EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface
{
    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
    }
}
