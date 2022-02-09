<?php

namespace EfTech\BookLibrary\Infrastructure\Router;

use Psr\Http\Message\ServerRequestInterface;

class ChainRouters implements RouterInterface
{
    /** Цепочка роутеров
     * @var RouterInterface[]
     */
    private array $routers;

    /**
     * @param RouterInterface ...$routers
     */
    public function __construct(RouterInterface ...$routers)
    {
        $this->routers = $routers;
    }


    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequestInterface $serverRequest): ?callable
    {
        $dispatcher = null;
        foreach ($this->routers as $router) {
            $currentDispatcher = $router->getDispatcher($serverRequest);
            if (is_callable($currentDispatcher)) {
                $dispatcher = $currentDispatcher;
                break;
            }
        }
        return $dispatcher;
    }
}
