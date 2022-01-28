<?php

namespace EfTech\FrameworkTest\Infrastrucrute\Uri;

use EfTech\BookLibrary\Infrastructure\Uri\Uri;
use PHPUnit\Framework\TestCase;

/**
 *  Тестирование uri
 */
class UriTest extends TestCase
{
    /** Тестирование преобразования объекта URI в строку
     *
     */
    public function testUriToString(): void
    {
        // Arrange
        $expected = 'http://and:mypassword@htmlbook.ru:80/' .
            'samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        $uri = new Uri(
            'http',
            'htmlbook.ru',
            '80',
            '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki',
            'query=value1',
            'and:mypassword',
            'fragment-example'
        );
        //Act
        $actual = (string)$uri;
        //Assert
        $this->assertEquals($expected, $actual, 'объект uri не корректно создан из строки');
    }
    /** Тестирование создание объекта URI из строки
     *
     */
    public function testCreateFromString(): void
    {
// Arrange
        $expected = 'http://and:mypassword@htmlbook.ru:80/samhtml/' .
            'ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        $uri = Uri::createFromString($expected);

        //Act
        $actual = (string)$uri;

        //Assert
        $this->assertEquals($expected, $actual, 'объект uri не корректно создан из строки');
    }
}
