<?php

namespace EfTech\BookLibrary\Infrastructure\Router;

use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Роутер сопоставляющий регулярные выражения и обработчик
 */
final class RegExpRouter implements RouterInterface
{
    /** Массив в котором сопсоставлены регулярные выражения и обработчки
     * @var array
     */
    private array $handlers;

    /** фабрика по созданию конроллеров
     * @var ControllerFactory
     */
    private ControllerFactory $controllerFactory;

    /**
     * @param array $handlers Массив в котором сопсоставлены регулярные выражения и обработчки
     * @param ControllerFactory $controllerFactory фабрика по созданию конроллеров
     */
    public function __construct(array $handlers, ControllerFactory $controllerFactory)
    {
        $this->handlers = $handlers;
        $this->controllerFactory = $controllerFactory;
    }


    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequestInterface $serverRequest): ?callable
    {
        $urlPath = $serverRequest->getUri()->getPath();

        $dispatcher = null;
        foreach ($this->handlers as $pattern => $currentDispatcher) {
            $matches = [];
            if (1 === preg_match($pattern, $urlPath, $matches)) {
                if (is_callable($currentDispatcher)) {
                    $dispatcher = $currentDispatcher;
                } elseif (
                    is_string($currentDispatcher)
                    && is_subclass_of($currentDispatcher, ControllerInterface::class, true)
                ) {
                    $dispatcher = $this->controllerFactory->create($currentDispatcher);
                }
                if (null !== $dispatcher) {
                    $serverRequestAttributes = $this->buildServersRequestAttributes($matches);
                    foreach ($serverRequestAttributes as $serverRequestAttributeName => $serverRequestAttributeValue) {
                        //TODO помнить про имутабельность
                        $serverRequest->withAttribute($serverRequestAttributeName, $serverRequestAttributeValue);
                    }
                    break;
                }
            }
        }
        return $dispatcher;
    }

    /** Получение аттрибутов серверного запроса
     * @param array $matches
     * @return array
     */
    private function buildServersRequestAttributes(array $matches): array
    {
        $attributes = [];

        foreach ($matches as $key => $value) {
            if (0 === strpos($key, '___') && '___' === substr($key, -3) && strlen($key) > 6) {
                $attributes[$this->buildAttrName($key)] = $value;
            }
        }
        return $attributes;
    }

    /** Получать имя аттрибута
     * @param string $groupName
     * @return string
     */
    private function buildAttrName(string $groupName): string
    {
        $clearAttrName = strtolower(substr($groupName, 3, -3));

        $parts = explode('_', $clearAttrName);

        $ucParts = array_map('ucfirst', $parts);
        return lcfirst(implode('', $ucParts));
    }
}
