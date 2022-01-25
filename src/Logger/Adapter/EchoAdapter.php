<?php

namespace EfTech\BookLibrary\Infrastructure\Logger\Adapter;

class EchoAdapter implements \EfTech\BookLibrary\Infrastructure\Logger\AdapterInterface
{

    /**
     * @inheritDoc
     */
    public function write(string $logLevel, string $msg): void
    {
        echo "$msg\n";
    }
}