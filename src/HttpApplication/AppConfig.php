<?php

namespace EfTech\BookLibrary\Infrastructure\HttpApplication;

class AppConfig implements AppConfigInterface
{

    /** Скрывает сообщения о ошибках
     * @var bool
     */
    private bool $hideErrorMsg = false;

    /** Возвращает флаг, который указывает что нужно скрывать сообщения о ощибках
     * @return bool
     */
    public function isHideErrorMsg(): bool
    {
        return $this->hideErrorMsg;
    }

    /** Устанавливает флаг указывающий что нужно скрывать сообщение о ошибках
     * @param bool $hideErrorMsg
     */
    private function setHideErrorMsg(bool $hideErrorMsg): void
    {
        $this->hideErrorMsg = $hideErrorMsg;
    }


    /**Создает конфиг приложения из массива
     * @param array $config
     * @return static
     * @uses \EfTech\BookLibrary\Infrastructure\HttpApplication\AppConfig::setHideErrorMsg()
     */
    public static function createFromArray(array $config): AppConfigInterface
    {
        $appConfig = new static();

        foreach ($config as $key => $value) {
            if (property_exists($appConfig, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfig->{$setter}($value);
            }
        }

        return $appConfig;
    }
}