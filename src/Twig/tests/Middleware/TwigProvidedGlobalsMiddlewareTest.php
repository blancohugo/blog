<?php

namespace BlancoHugoTest\Blog\Twig\Middleware;

use BlancoHugo\Blog\Twig\Middleware\TwigProvidedGlobalsMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class TwigProvidedGlobalsMiddlewareTest extends TestCase
{
    public function getEnvironment(): Environment
    {
        return $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function getResponse(): ResponseInterface
    {
        return $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
    }

    public function getServerRequest(): ServerRequestInterface
    {
        return $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
    }

    public function getRequestHandler(): RequestHandlerInterface
    {
        $mock = $this->getMockBuilder(RequestHandlerInterface::class)
            ->getMock();
        $mock->method('handle')
            ->willReturn($this->getResponse());

        return $mock;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithGlobals()
    {
        $globals = [
            'now' => fn() => new Date('now'),
            'tomorrow' => fn() => new Date('+1 day')
        ];
        $middleware = new TwigProvidedGlobalsMiddleware($this->getEnvironment(), $globals);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithNoGlobals()
    {
        $middleware = new TwigProvidedGlobalsMiddleware($this->getEnvironment(), []);
    }

    /**
     * @test
     */
    public function globalsWithNonStringIndexShouldNotBeAdded()
    {
        $globals = [
            'now' => fn() => new \DateTime('now'),
            5 => fn() => new \DateTime('+1 day')
        ];

        $environment = $this->getEnvironment();
        $environment->expects($this->once())
            ->method('addGlobal');

        $middleware = new TwigProvidedGlobalsMiddleware($environment, $globals);
        $middleware->process($this->getServerRequest(), $this->getRequestHandler());
    }

    /**
     * @test
     */
    public function globalsWithNonCallableValueShouldNotBeAdded()
    {
        $globals = [
            'now' => fn() => new \DateTime('now'),
            'tomorrow' => new \DateTime('+1 day')
        ];

        $environment = $this->getEnvironment();
        $environment->expects($this->once())
            ->method('addGlobal');

        $middleware = new TwigProvidedGlobalsMiddleware($environment, $globals);
        $middleware->process($this->getServerRequest(), $this->getRequestHandler());
    }

    /**
     * @test
     */
    public function validGlobalsShouldBeAddedToEnvironment()
    {
        $globals = [
            'now' => fn() => new \DateTime('now'),
            'tomorrow' => fn() => new \DateTime('+1 day')
        ];

        $environment = $this->getEnvironment();
        $environment->expects($this->exactly(2))
            ->method('addGlobal');

        $middleware = new TwigProvidedGlobalsMiddleware($environment, $globals);
        $middleware->process($this->getServerRequest(), $this->getRequestHandler());
    }
}
