<?php

namespace EfTech\FrameworkTest\Infrastrucrute\DI;

use EfTech\BookLibrary\Infrastructure\DI\ServiceManager;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceManagerTest extends TestCase
{
    public static function hasDataProvider(): array
    {
        return [
            'Возможность получить сервис, добавленный в сервис менеджер' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$factories' => [
                    'test2' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test1',
                'expectedResult' => true
            ],
            'Возможность получить сервис, для которого есть фабрика в сервис менеджере' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$factories' => [
                    'test2' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test2',
                'expectedResult' => true
            ],
            'проверка что отсутствует возможность получить из ServiceManager неизвестный серсис' => [
                '$instance' => [
                    'test1' => new stdClass()
                ],
                '$factories' => [
                    'test2' => static function () {
                        return new stdClass();
                    }
                ],
                'serviceName' => 'test3',
                'expectedResult' => false
            ]
        ];
    }
    /**
     * @dataProvider hasDataProvider
     * @param array $instance - коллекция сервисов, зареганых в сервис локаторе
     * @param array $factories - фабрики
     * @param string $serviceName - имя сервиса наличие которого проверяется в локаторе сервисов
     * @param bool $expectedResult - ожидаемый результат
     */
    public function testHas(array $instance, array $factories, string $serviceName, bool $expectedResult): void
    {
        //Arrange
        $sl = new ServiceManager($instance, $factories);

        //Action
        $actualResult = $sl->has($serviceName);

        //Assert
        $this->assertEquals($expectedResult, $actualResult, 'Ошибки работы метода has менеджера сервисов');
    }
}
