<?php

namespace EfTech\BookLibrary\Infrastructure\ViewTemplate;
/**
 *  Интерфейс шаблонизаторов html
 */
interface ViewTemplateInterface
{
    /** рендерит данные
     * @param string $template путь для шаблона
     * @param array $context - данные для рендеринга
     * @return string - результат рендеринга
     */
    public function render(string $template,array $context):string;
}