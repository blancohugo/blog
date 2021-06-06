<?php

namespace BlancoHugoTest\Blog\Page\Handler;

use BlancoHugo\Blog\Page\Handler\GetPageDetailHandler;
use BlancoHugo\Blog\Page\Model\Page;
use BlancoHugo\Blog\Page\Repository\PageRepositoryInterface;
use Mezzio\Template\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetPageDetailHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function getTemplateRenderer(): TemplateRendererInterface
    {
        return $this->getMockBuilder(TemplateRendererInterface::class)
            ->getMock();
    }

    public function getRepository($findReturn = null): PageRepositoryInterface
    {
        $mock = $this->getMockBuilder(PageRepositoryInterface::class)
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
        $handler = new GetPageDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository()
        );
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithoutPageParameter()
    {
        $request = $this->getRequest();
        $request->getAttribute('page')->willReturn(null);

        $handler = new GetPageDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository()
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithNonStringPageParameter()
    {
        $request = $this->getRequest();
        $request->getAttribute('page')->willReturn(5);

        $handler = new GetPageDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository()
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithInexistentPage()
    {
        $request = $this->getRequest();
        $request->getAttribute('page')->willReturn('page-slug');

        $handler = new GetPageDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository(null)
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }

    /**
     * @test
     */
    public function requestCanBeHandledWithValidPage()
    {
        $request = $this->getRequest();
        $request->getAttribute('page')->willReturn('page-slug');

        $handler = new GetPageDetailHandler(
            $this->getTemplateRenderer(),
            $this->getRepository(new Page('Title', 'my-page', 'my content'))
        );
        $this->assertInstanceOf(ResponseInterface::class, $handler->handle($request->reveal()));
    }
}
