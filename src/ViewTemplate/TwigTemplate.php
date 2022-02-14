<?php

namespace EfTech\BookLibrary\Infrastructure\ViewTemplate;

use Twig\Environment;
use Twig;
use Twig\Loader\FilesystemLoader;

class TwigTemplate implements ViewTemplateInterface
{
    /** Путь до директории с шаблонами
     * @var string
     */
    private string $pathToTemplates;
    /** Шаблонизатор twig
     * @var Environment|null
     */
    private ?Environment $twig = null;

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        if (null === $this->twig) {
            $loader = new FilesystemLoader($this->pathToTemplates);
            $twig = new Environment($loader);
            $this->twig = $twig;
        }
        return $this->twig;
    }


    /**
     * @param string $pathToTemplates
     */
    public function __construct(string $pathToTemplates)
    {
        $this->pathToTemplates = $pathToTemplates;
    }

    /**
     * @param string $template
     * @param array $context
     * @return string
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    public function render(string $template, array $context): string
    {
        return $this->getTwig()->render($template, $context);
    }
}
