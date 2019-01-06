<?php

namespace Tests\Router;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Piface\Router\Route;
use Piface\Router\RouterContainer;

class RouterContainerTest extends TestCase
{
    /**
     * @var RouterContainer
     */
    private $routerContainer;

    public function setUp()
    {
        $this->routerContainer = new RouterContainer();
    }

    public function testMatch()
    {
        $route = new Route('GET', '/user/{id}', 'user', function () {});
        $request = new ServerRequest('GET', '/user/3');
        $match = $this->routerContainer->match($request, $route);

        $this->assertEquals(true, $match);
    }

    public function testMatchWithCustomRegex()
    {
        $route = new Route('GET', '/user/{id}', 'user', function () {});
        $route->where(['id' => '[a-z]+']);
        $invalidRequest = new ServerRequest('GET', '/user/3');
        $validRequest = new ServerRequest('GET', '/user/zd');

        $noResult = $this->routerContainer->match($invalidRequest, $route);
        $result = $this->routerContainer->match($validRequest, $route);

        $this->assertEquals(false, $noResult);
        $this->assertEquals(true, $result);
    }
}
