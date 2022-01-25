<?php

namespace EfTech\BookLibrary\Infrastructure\HttpApplication;
use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use EfTech\BookLibrary\Infrastructure\http\httpResponse;
use EfTech\BookLibrary\Infrastructure\http\ServerRequest;
use EfTech\BookLibrary\Infrastructure\http\ServerResponseFactory;
use EfTech\BookLibrary\Infrastructure\Logger\LoggerInterface;
use EfTech\BookLibrary\Infrastructure\Router\RouterInterface;
use Throwable;
use EfTech\BookLibrary\Infrastructure\Exception;
use EfTech\BookLibrary\Infrastructure\View\RenderInterface;
/**
 * Ядро приложения
 */
final class App
{

    /** Конфиг приложения
     * @var AppConfigInterface|null
     */
    private ?AppConfigInterface $appConfig = null;
    /** Логирование
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger = null;

    /** Компонент отвечающий за рендеринг
     * @var RenderInterface|null
     */
    private ?RenderInterface $render = null;
    /** Локатор сервисов
     * @var ContainerInterface |null
     */
    private ?ContainerInterface $container = null;

    /**
     * @param callable $routerFactory Фабрика реализующая логику создания роутера
     * @param callable $loggerFactory Фабрика реализующая логику создания логгеров
     * @param callable $appConfigFactory  Фабрика реализующая логику создания конфига приложения
     * @param callable $renderFactory Фабрика реализующая логику создания рендера
     * @param callable $diContainerFactory Фабрика реализующая логику создания di контейнера
     */
    public function __construct(
        callable $routerFactory,
        callable $loggerFactory,
        callable $appConfigFactory,
        callable $renderFactory,
        callable $diContainerFactory
    ) {
        $this->routerFactory = $routerFactory;
        $this->loggerFactory = $loggerFactory;
        $this->appConfigFactory = $appConfigFactory;
        $this->renderFactory = $renderFactory;
        $this->diContainerFactory = $diContainerFactory;
        $this->initErrorHandling();
    }

    /** Возвращает роутер
     * @return RouterInterface
     */
    private function getRouter(): RouterInterface
    {
        if (null === $this->router) {
            $this->router = ($this->routerFactory)($this->getContainer());
        }
        return $this->router;
    }

    /**
     * @return AppConfigInterface|null
     */
    private function getAppConfig(): AppConfigInterface
    {
        if (null === $this->appConfig) {
            $this->appConfig = ($this->appConfigFactory)($this->getContainer());
        }
        return $this->appConfig;
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        if (null === $this->logger) {
            $this->logger = ($this->loggerFactory)($this->getContainer());
        }
        return $this->logger;
    }

    /**
     * @return RenderInterface
     */
    private function getRender(): RenderInterface
    {
        if (null === $this->render) {
            $this->render = ($this->renderFactory)($this->getContainer());
        }
        return $this->render;
    }

    /**
     * @return ContainerInterface |null
     */
    private function getContainer():ContainerInterface
    {
        if (null === $this->container) {
            $this->container = ($this->diContainerFactory)();
        }
        return $this->container;
    }


    /** Компанент отвечающий за роутинг запросов
     * @var RouterInterface|null
     */
    private ?RouterInterface $router = null;

    /** Фабрика реализующая роутер
     * @var callable
     */
    private $routerFactory;



    /** Инициация обработки ошибок
     *
     */
    private function initErrorHandling():void
    {
        set_error_handler(static function(int $errNom, string $errStr , $errFile, $errLine){
            throw new RuntimeException($errStr);
        });
    }
    /** Фабрика реализующая логику создания логгеров
     * @var callable
     */
    private $loggerFactory;
    /** Фабрика реализующая логику создания конфига приложения
     * @var callable
     */
    private $appConfigFactory;
    /** Фабрика реализующая логику создания рендера
     * @var callable
     */
    private $renderFactory;
    /** Фабрика реализующая логику создания di контейнера
     * @var callable
     */
    private $diContainerFactory;

    /** Обработчик запроса
     * @param ServerRequest $serverRequest - объект серверного http запроса
     * @return httpResponse - реез ответ
     */
    public function dispath(ServerRequest $serverRequest):httpResponse
    {
        $hasAppConfig = false;
        try {
            $hasAppConfig = $this->getAppConfig() instanceof AppConfigInterface;
            $logger = $this->getLogger();

            $urlPath = $serverRequest->getUri()->getPath();
            $logger->info('Url request received' . $urlPath);
            $dispatcher = $this->getRouter()->getDispatcher($serverRequest);
            if(is_callable($dispatcher)) {
                $httpResponse = $dispatcher($serverRequest);
                if (!($httpResponse instanceof httpResponse)) {
                    throw new Exception\UnexpectedValueException('Контроллер вернул некорректный результат');
                }
            } else {
                $httpResponse = ServerResponseFactory::createJsonResponse(
                    404,
                    ['status' => 'fail', 'message' => 'unsupported request']
                );
            }
            $this->getRender()->render($httpResponse);
        } catch (Exception\InvalidDataStructureException $e) {
            $httpResponse = ServerResponseFactory::createJsonResponse(
                503,
                ['status' => 'fail', 'message' => $e->getMessage()]
            );
            $this->silentRender($httpResponse);
        } catch (Throwable $e) {
            $errMsg = ($hasAppConfig && !$this->getAppConfig()->isHideErrorMsg())
                || $e instanceof Exception\ErrorCreateAppConfigException
                ? $e->getMessage()
                : 'system error';

            $this->silentLog($e->getMessage());

            $httpResponse = ServerResponseFactory::createJsonResponse(
                500,
                ['status' => 'fail', 'message' => $errMsg]
            );
            $this->silentRender($httpResponse);
        }

        return $httpResponse;
    }

    /** Тихое отображение данных - если отправка данных пользователю закончилось ошибкой, то это никак не влияет
     * @param httpResponse $httpResponse - http ответ
     */
    private function silentRender(httpResponse $httpResponse): void
    {
        try {
            $this->getRender()->render($httpResponse);
        } catch (Throwable $e) {
            $this->silentLog($e->getMessage());
        }
    }

    /** Тихое логгирование - если отправка данных пользователю закончилось ошибкой, то это никак не влияет
     * @param string $msg - сообщение в логи
     */
    private function silentLog(string $msg):void
    {
        try {
            $this->logger->error($msg);
        } catch (Throwable $e) {

        }
    }

}