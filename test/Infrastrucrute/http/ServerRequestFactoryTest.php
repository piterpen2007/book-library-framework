<?php

namespace EfTech\FrameworkTest\Infrastrucrute\http;

use EfTech\BookLibrary\Infrastructure\http\ServerRequestFactory;
use PHPUnit\Framework\TestCase;

/**
 *  Тестирует логику работу фабрики, создающий серверный http запрос
 */
class ServerRequestFactoryTest extends TestCase
{
    /**
     *  Тестирует логику работу фабрики, создающий серверный http запрос
     */
    public function testCreateFromGlobals(): void
    {
        //Arrange
        $servers = [
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SERVER_PORT' => '80',
            'REQUEST_URI' => '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example',
            'REQUEST_METHOD' => 'GET',
            'SERVER_NAME' => 'localhost',

            'HTTP_HOST'       =>  'localhost:80',
            'HTTP_CONNECTION' =>  'Keep-Alive',
            'HTTP_USER_AGENT' =>  'Apache-HttpClient\/4.5.13 (Java\/11.0.11)',
            'HTTP_COOKIE'     =>  'XDEBUG_SESSION=16151',
        ];
        $expectedBody = 'test';
        $expectedQueryParams = [
            'query' => 'value1'
        ];
        $expectedUri = 'http://localhost:80/samhtml/ssylki/' .
            'absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        $expectedRequestTarget = '/samhtml/ssylki/absolyutnye-i-otnositelnye-ssylki?query=value1#fragment-example';
        $expectedMethod = 'GET';
        $expectedProtocolVersion = '1.1';

        //Act
        $httpServerRequest = ServerRequestFactory::createFromGlobals($servers, $expectedBody);
        $actual = (string)$httpServerRequest->getUri();

        //Assert
        $this->assertEquals($expectedUri, $actual, 'Объект URI не корректный');
        $this->assertEquals($expectedBody, $httpServerRequest->getBody(), 'Не корректное тело запроса');
        $this->assertEquals($expectedMethod, $httpServerRequest->getMethod(), 'Не корректный тип http метода');
        $this->assertEquals(
            $expectedProtocolVersion,
            $httpServerRequest->getProtocolVersion(),
            'Не корректная версия протокола'
        );
        $this->assertEquals(
            $expectedRequestTarget,
            $httpServerRequest->getRequestTarget(),
            'Не корректная цель http запроса'
        );
        $this->assertEquals(
            $expectedQueryParams,
            $httpServerRequest->getQueryParams(),
            'Данные параметров запроса не валидны'
        );
    }
}
