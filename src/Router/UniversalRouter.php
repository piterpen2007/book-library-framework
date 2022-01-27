<?php

namespace EfTech\BookLibrary\Infrastructure\Router;

use EfTech\BookLibrary\Infrastructure\Controller\ControllerInterface;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;

/**
 *  Унивирсальный роутер
 */
class UniversalRouter implements RouterInterface
{
    private const URL_PATTERN = '/^\/(?<___RESOURCE_NAME___>[a-zA-Z][a-zA-Z0-9\-]*)' .
    '(\/(?<___RESOURCE_ID___>[0-9]+))?(\/(?<___SUB_ACTION___>[a-zA-Z0-9\-]*))?\/?$/';
    private const URL_METHOD_TO_ACTION = [
      'GET' => 'Get',
      'POST' => 'Create',
      'PUT' => 'Update',
      'DELETE' => 'Delete'
    ];
    /** фабрика по созданию конроллеров
     * @var ControllerFactory
     */
    private ControllerFactory $controllerFactory;
    /** Пространство имен в котором распологаются контроллеры приложения
     * @var string
     */
    private string $controllerNs;

    /**
     * @param ControllerFactory $controllerFactory фабрика по созданию конроллеров
     * @param string $controllerNs Пространство имен в котором распологаются контроллеры приложения
     */
    public function __construct(ControllerFactory $controllerFactory, string $controllerNs)
    {
        $this->controllerFactory = $controllerFactory;
        $this->controllerNs = trim($controllerNs, '\\') . '\\';
    }

    /**
     * @inheritDoc
     */
    public function getDispatcher(ServerRequest $serverRequest): ?callable
    {
        $dispatcher = null;
        $urlPath = $serverRequest->getUri()->getPath();
        $method = $serverRequest->getMethod();


        $matches = [];

        if (
            array_key_exists($method, self::URL_METHOD_TO_ACTION)
            && preg_match(self::URL_PATTERN, $urlPath, $matches)
        ) {
            $action = self::URL_METHOD_TO_ACTION[$method];
            $resource = ucfirst($matches['___RESOURCE_NAME___']);

            $subAction = array_key_exists('___SUB_ACTION___', $matches) ? ucfirst($matches['___SUB_ACTION___']) : '';
            $attr = [];
            if ('POST' === $method) {
                $suffix = 'Controller';
            } elseif (array_key_exists('___RESOURCE_ID___', $matches)) {
                $suffix = 'Controller';
                $attr['id'] = $matches['___RESOURCE_ID___'];
            } else {
                $suffix = 'CollectionController';
            }
            $className = $action . $subAction . $resource . $suffix;
            $fullClassName = $this->controllerNs . $className;
            if (
                class_exists($fullClassName)
                && is_subclass_of($fullClassName, ControllerInterface::class, true)
            ) {
                $dispatcher = $this->controllerFactory->create($fullClassName);
                $serverRequest->setAttributes($attr);
            }
        }
        return $dispatcher;
    }
}
