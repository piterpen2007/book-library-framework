<?php

namespace EfTech\BookLibrary\Infrastructure\ViewTemplate;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;

class PhtmlTemplate implements ViewTemplateInterface
{

    /**
     * @inheritDoc
     */
    public function render(string $template, array $context): string
    {
        if (false === file_exists($template)) {
            throw new RuntimeException("некорректный путь до шаблона '$template'");
        }
        extract($context,EXTR_SKIP);
        unset($context);
        ob_start();
        require $template;

        return ob_get_clean();
    }
}