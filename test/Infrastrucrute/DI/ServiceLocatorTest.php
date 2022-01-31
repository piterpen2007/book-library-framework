<?php

namespace EfTech\FrameworkTest\Infrastrucrute\DI;

use EfTech\BookLibrary\Infrastructure\DI\ServiceLocator;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorTest extends TestCase
{
    public static function hasDataProvider(): array
    {
        return [
            'Возможность получить из контейнера сервис, предварительно добавленный в контейнер' => [
                '$instance' => [
                    'test' => new stdClass()
                ],
                'serviceName' => 'test',
                'expectedResult' => true
            ],
            'Возможность получить из контейнера сервис, отсутствующий в контейнере' => [
                '$instance' => [
                    'test' => new stdClass()
                ],
                'serviceName' => 'test123',
                'expectedResult' => false
            ]
        ];
    }
    /**
     * @dataProvider hasDataProvider
     * @param array $instance - коллекция сервисов, зареганых в сервис локаторе
     * @param string $serviceName - имя сервиса наличие которого проверяется в локаторе сервисов
     * @param bool $expectedResult - ожидаемый результат
     */
    public function testHas(array $instance, string $serviceName, bool $expectedResult): void
    {
        //Arrange
        $sl = new ServiceLocator($instance);

        //Action
        $actualResult = $sl->has($serviceName);

        //Assert
        $this->assertEquals($expectedResult, $actualResult, 'Ошибки работы метода has локатора сервисов');
    }
}
