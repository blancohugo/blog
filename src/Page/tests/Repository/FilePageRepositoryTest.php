<?php

namespace BlancoHugoTest\Blog\Page\Repository;

use BlancoHugo\Blog\Page\Model\Page;
use BlancoHugo\Blog\Page\Repository\FilePageRepository;
use BlancoHugo\Blog\Page\Service\CreatePageFromFileService;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class FilePageRepositoryTest extends TestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $hierarchy = [
            'resources' => [
                'about-me.md' => "Title\r\nLine1\r\nLine2\r\nLine3",
                'my-projects.md' => "Title\r\nLine1",
                'contact.md' => '',
                'note.txt' => 'text file'
            ]
        ];

        $this->root = vfsStream::setup('root', 755, $hierarchy);
        $this->pages = [
            'about-me' => (new Page('About me', 'about-me', 'This is me'))->setPublished(true),
            'my-projects' => (new Page('My projects', 'my-projects', 'There is no project'))->setPublished(true),
            'contact' => (new Page('Contact', 'contact', 'Don\'t do that'))->setPublished(false)
        ];
    }

    public function getCreateService(): ObjectProphecy
    {
        $baseUrl = $this->root->url() . '/resources';

        $prophecy = $this->prophesize(CreatePageFromFileService::class);
        $prophecy->__invoke("{$baseUrl}/about-me.md")->willReturn($this->pages['about-me']);
        $prophecy->__invoke("{$baseUrl}/my-projects.md")->willReturn($this->pages['my-projects']);
        $prophecy->__invoke("{$baseUrl}/contact.md")->willReturn($this->pages['contact']);

        return $prophecy;
    }

    /**
     * @test
     */
    public function objectCannotBeConstructedWithInvalidPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            'invalid/directory',
            'md'
        );
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithValidPath()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );
        return $repository;
    }

    /**
     * @test
     */
    public function pageShouldNotBeLoadedTwice()
    {
        $createService = $this->getCreateService();
        $repository = new FilePageRepository(
            $createService->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $repository->load('about-me');
        $repository->load('about-me');

        $createService->__invoke(Argument::any())->shouldBeCalledTimes(1);
    }

    /**
     * @test
     */
    public function pageCanBeFoundBySlug()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $this->assertInstanceOf(Page::class, $repository->findBySlug('contact'));
        $this->assertInstanceOf(Page::class, $repository->findBySlug('about-me'));
        $this->assertInstanceOf(Page::class, $repository->findBySlug('my-projects'));
    }

    /**
     * @test
     */
    public function inexistentSlugShouldReturnNull()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $this->assertNull($repository->findBySlug('inexistent-slug'));
    }

    /**
     * @test
     */
    public function findAllCanReturnAllPages()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $this->assertEquals(3, count($repository->findAll()));
    }

    /**
     * @test
     */
    public function findAllCanBeLimited()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $expectedArray = ['my-projects' => $this->pages['my-projects']];
        $this->assertSame($expectedArray, $repository->findAll(1));
    }

    /**
     * @test
     */
    public function pagesCanBeFilteredByPublished()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $expectedArray = [
            'my-projects' => $this->pages['my-projects'],
            'about-me' => $this->pages['about-me']
        ];
        $this->assertSame($expectedArray, $repository->findPublished());
    }

    /**
     * @test
     */
    public function pagesFilteredByPublishedCanBeLimited()
    {
        $repository = new FilePageRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $expectedArray = ['my-projects' => $this->pages['my-projects']];
        $this->assertSame($expectedArray, $repository->findPublished(1));
    }
}
