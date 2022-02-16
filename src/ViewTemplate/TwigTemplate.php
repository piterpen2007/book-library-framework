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
    /** Режим отладки
     * @var bool
     */
    private bool $debug = false;

    /** Директория кеширования
     * @var string|null
     */
    private ?string $cacheDir;
    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        if (null === $this->twig) {
            $loader = new FilesystemLoader($this->pathToTemplates);
            $twig = new Environment(
                $loader,
                [
                    'cache' => null === $this->cacheDir ? false : $this->cacheDir,
                    'debug' => $this->debug
                ]
            );
            $this->twig = $twig;
        }
        return $this->twig;
    }


    /**
     * @param string $pathToTemplates
     * @param string|null $cacheDir
     * @param bool $debug
     */
    public function __construct(string $pathToTemplates, string $cacheDir = null, bool $debug = false)
    {
        $this->pathToTemplates = $pathToTemplates;
        $this->cacheDir = $cacheDir;
        $this->debug = $debug;
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
