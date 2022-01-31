<?php

namespace EfTech\FrameworkTest\Infrastrucrute\DI;

use EfTech\BookLibrary\Infrastructure\DI\Container;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 *  Тестирование di контейнера
 */
class ContainerTest extends TestCase
{
    public static function hasDataProvider(): array
    {
        return [
            'Возможность получить сервис, добавленный в di контейнер' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$services' => [
                    'test2' => ['class' => new stdClass()]
                ],
                '$factories' => [
                    'test3' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test1',
                'expectedResult' => true
            ],
            'Возможность получить сервис, для которого есть описание зависимостей' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$services' => [
                    'test2' => ['class' => new stdClass()]
                ],
                '$factories' => [
                    'test3' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test2',
                'expectedResult' => true
            ],
            'Возможность получить сервис, для которого есть фабрика в di контейнере' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$services' => [
                    'test2' => ['class' => new stdClass()]
                ],
                '$factories' => [
                    'test3' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test3',
                'expectedResult' => true
            ],
            'проверка что отсутствует ввозможность получить сервис, которого нет в контейнере' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$services' => [
                    'test2' => ['class' => new stdClass()]
                ],
                '$factories' => [
                    'test3' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test4',
                'expectedResult' => false
            ]
        ];
    }

    /**
     * Тестирование метода  has di контейнера
     * @dataProvider hasDataProvider
     * @param array $instance - коллекция сервисов, зареганых в сервис локаторе
     * @param array $factories
     * @param array $services - коллекция данных описывающих зависимости сервисов
     * @param string $serviceName
     * @param bool $expectedResult
     */
    public function testHas(
        array $instance,
        array $factories,
        array $services,
        string $serviceName,
        bool $expectedResult
    ): void {
        //Arrange
        $sl = new Container($instance, $services, $factories);

        //Action
        $actualResult = $sl->has($serviceName);

        //Assert
        $this->assertEquals($expectedResult, $actualResult, 'Ошибки работы метода has менеджера сервисов');
    }
}
