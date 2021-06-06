<?php

namespace BlancoHugoTest\Blog\Post\Handler;

use BlancoHugo\Blog\Post\Handler\GetPostDetailHandler;
use BlancoHugo\Blog\Post\Model\Post;
use BlancoHugo\Blog\Post\Repository\PostRepositoryInterface;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPostDetailHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function getTemplateRenderer(): TemplateRendererInterface
    {
        return $this->getMockBuilder(TemplateRendererInterface::class)
            ->getMock();
    }

    public function getRepository($findReturn = null): PostRepositoryInterface
    {
        $mock = $this->getMockBuilder(PostRepositoryInterface::class)
            ->getMock();

        $mock->method('findBySlug')->willReturn($findReturn);
        return $mock;
    }

    public function getRequest(): ObjectProphecy
    {
        return $this->prophesize(ServerRequestInterface::class);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $handler = new GetPostDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository()
        );
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithoutPostParameter()
    {
        $request = $this->getRequest();
        $request->getAttribute('post')->willReturn(null);

        $handler = new GetPostDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository()
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithNonStringPostParameter()
    {
        $request = $this->getRequest();
        $request->getAttribute('post')->willReturn(8);

        $handler = new GetPostDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository()
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithInexistentPost()
    {
        $request = $this->getRequest();
        $request->getAttribute('post')->willReturn('post-slug');

        $handler = new GetPostDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository(null)
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithValidPost()
    {
        $request = $this->getRequest();
        $request->getAttribute('post')->willReturn('post-slug');

        $handler = new GetPostDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository(new Post('Title', 'My summary', 'my-post', 'My content'))
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }
}
