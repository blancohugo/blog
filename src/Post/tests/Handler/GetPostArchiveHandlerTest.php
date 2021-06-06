<?php

namespace BlancoHugoTest\Blog\Post\Handler;

use BlancoHugo\Blog\Post\Handler\GetPostArchiveHandler;
use BlancoHugo\Blog\Post\Model\Post;
use BlancoHugo\Blog\Post\Repository\PostRepositoryInterface;
use BlancoHugo\Blog\Post\Service\GroupPostsByDateService;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPostArchiveHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function getTemplateRenderer(): TemplateRendererInterface
    {
        return $this->getMockBuilder(TemplateRendererInterface::class)
            ->getMock();
    }

    public function getRepository($findReturn = []): PostRepositoryInterface
    {
        $mock = $this->getMockBuilder(PostRepositoryInterface::class)
            ->getMock();

        $mock->method('findAll')->willReturn($findReturn);
        return $mock;
    }

    public function getGrouper(): GroupPostsByDateService
    {
        $mock = $this->getMockBuilder(GroupPostsByDateService::class)
            ->getMock();

        $mock->method('__invoke')
            ->will($this->returnArgument(0));

        return $mock;
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
        $handler = new GetPostArchiveHandler(
            $this->getTemplateRenderer(),
            $this->getRepository(),
            $this->getGrouper()
        );
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithNoPosts()
    {
        $repository = $this->getRepository([]);
        $repository->expects($this->once())
            ->method('findAll');

        $handler = new GetPostArchiveHandler(
            $this->getTemplateRenderer(),
            $repository,
            $this->getGrouper()
        );

        $response = $handler->handle($this->getRequest());
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithPosts()
    {
        $posts = [
            new Post('My title', 'Summary', 'my-post', 'My content'),
            new Post('My title', 'Summary', 'my-post', 'My content'),
            new Post('My title', 'Summary', 'my-post', 'My content'),
        ];

        $repository = $this->getRepository($posts);
        $repository->expects($this->once())
            ->method('findAll');

        $handler = new GetPostArchiveHandler(
            $this->getTemplateRenderer(),
            $repository,
            $this->getGrouper()
        );

        $response = $handler->handle($this->getRequest());
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
