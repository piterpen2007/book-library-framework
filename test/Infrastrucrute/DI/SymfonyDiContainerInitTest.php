<?php

namespace EfTech\FrameworkTest\Infrastrucrute\DI;

use EfTech\BookLibrary\Infrastructure\DI\SymfonyDiContainerInit;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *  Тестирование компанента создающего di container Symfony
 */
class SymfonyDiContainerInitTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateDiContainer(): void
    {
        //Arrange
        $symfonyDiContainerInit = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/test-data/empty-di.xml',
            )
        );

        //Act
        $actualResult = $symfonyDiContainerInit();

        //Assert
        $this->assertInstanceOf(ContainerBuilder::class, $actualResult);
    }
}
