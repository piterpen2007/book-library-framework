<?php

namespace EfTech\FrameworkTest\Infrastructure\ViewTemplate;

use EfTech\BookLibrary\Infrastructure\ViewTemplate\ViewTemplateInterface;
use PHPUnit\Framework\TestCase;
use EfTech\BookLibrary\Infrastructure\ViewTemplate\TwigTemplate;

/**
 * Тестирование движка, отвечающего за рендеринг html. ДВижок является адаптером к шаблонизации twig
 */
class TwigTemplateTest extends TestCase
{
    /**
     *  Тестирование рендеринга с помощью движка, использующего шаблонизатор twig
     */
    public function testRender(): void
    {
        //Arrange
        /** @var ViewTemplateInterface $viewTemplate*/
        $viewTemplate = new TwigTemplate(__DIR__ . '/data/');
        //Act
        $actualResult = $viewTemplate->render('simple.template.twig', ['userName' => 'Dan']);
        //Assert
        $this->assertEquals("Hello, username Dan\n", $actualResult);
    }
}
