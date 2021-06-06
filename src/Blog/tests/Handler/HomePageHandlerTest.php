<?php

namespace BlancoHugoTest\Blog\Handler;

use BlancoHugo\Blog\Handler\HomePageHandler;
use BlancoHugo\Blog\Post\Repository\PostRepositoryInterface;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HomePageHandlerTest extends TestCase
{
    public function getTemplateRenderer(): TemplateRendererInterface
    {
        return $this->getMockBuilder(TemplateRendererInterface::class)
            ->getMock();
    }

    public function getRepository(): PostRepositoryInterface
    {
        return $this->getMockBuilder(PostRepositoryInterface::class)
            ->getMock();
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $handler = new HomePageHandler($this->getTemplateRenderer(), $this->getRepository());
    }

    /**
     * @test
     */
    public function requestCanBeHandled()
    {
        $handler = new HomePageHandler($this->getTemplateRenderer(), $this->getRepository());
        $request = $this->getRequest();

        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request));
    }
}
