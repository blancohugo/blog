<?php

namespace BlancoHugoTest\Blog\Twig\Middleware;

use BlancoHugo\Blog\Twig\Middleware\TwigProvidedGlobalsMiddleware;
use BlancoHugo\Blog\Twig\Middleware\TwigProvidedGlobalsMiddlewareFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Twig\Environment;

class TwigProvidedGlobalsMiddlewareFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function getContainer(array $config = []): ObjectProphecy
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(Argument::any())->willReturn(null);
        $container->get(Environment::class)->willReturn($this->getEnvironment());
        $container->get('config')->willReturn($config);
        $container->get('now')->willReturn(fn() => new \DateTime('now'));
        $container->get('tomorrow')->willReturn(fn() => new \DateTime('+1 day'));
        $container->has(Argument::any())->willReturn(false);
        $container->has(Environment::class)->willReturn(true);
        $container->has('config')->willReturn(true);
        $container->has('now')->willReturn(true);
        $container->has('tomorrow')->willReturn(true);

        return $container;
    }

    public function getEnvironment(): Environment
    {
        return $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $factory = new TwigProvidedGlobalsMiddlewareFactory();
        return $factory;
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateInstanceWithoutConfig()
    {
        $factory = new TwigProvidedGlobalsMiddlewareFactory();
        $middleware = $factory($this->getContainer([])->reveal());

        $this->assertInstanceOf(TwigProvidedGlobalsMiddleware::class, $middleware);
    }

    /**
     * @test
     */
    public function creationWithInvalidGlobalNameShouldThrowException()
    {
        $config = [
            'twig' => [
                'provided_globals' => [
                    'now_time' => 'now',
                    5 => 'tomorrow'
                ]
            ]
        ];

        $this->expectException(\RuntimeException::class);

        $factory = new TwigProvidedGlobalsMiddlewareFactory();
        $middleware = $factory($this->getContainer($config)->reveal());
    }

    /**
     * @test
     */
    public function creationWithInvalidGlobalValueShouldThrowException()
    {
        $config = [
            'twig' => [
                'provided_globals' => [
                    'now_time' => 'now',
                    'yesterday_time' => 'yesterday' // is not resolved by container
                ]
            ]
        ];

        $this->expectException(\RuntimeException::class);

        $factory = new TwigProvidedGlobalsMiddlewareFactory();
        $middleware = $factory($this->getContainer($config)->reveal());
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateInstanceWithValidConfig()
    {
        $config = [
            'twig' => [
                'provided_globals' => [
                    'now_time' => 'now',
                    'tomorrow_time' => 'tomorrow'
                ]
            ]
        ];

        $factory = new TwigProvidedGlobalsMiddlewareFactory();
        $middleware = $factory($this->getContainer($config)->reveal());

        $this->assertInstanceOf(TwigProvidedGlobalsMiddleware::class, $middleware);
    }
}
