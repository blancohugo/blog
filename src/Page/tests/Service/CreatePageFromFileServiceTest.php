<?php

namespace BlancoHugoTest\Blog\Page\Service;

use BlancoHugo\Blog\File\ResourceFile;
use BlancoHugo\Blog\File\ResourceFileFactory;
use BlancoHugo\Blog\Page\Model\Page;
use BlancoHugo\Blog\Page\Service\CreatePageFromFileService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreatePageFromFileServiceTest extends TestCase
{
    use ProphecyTrait;

    private function getFileFactory(ResourceFile $file = null): ResourceFileFactory
    {
        $mock = $this->getMockBuilder(ResourceFileFactory::class)
            ->getMock();
        $mock->method('__invoke')->willReturn($file);

        return $mock;
    }

    public function getResourceFile(): ObjectProphecy
    {
        $file = $this->prophesize(ResourceFile::class);
        $file->getFilename()->willReturn('my-page');
        $file->getContent()->willReturn('My content');
        $file->getMetadata('title', Argument::any())->will(fn($args) => $args[1]);
        $file->getMetadata('published', Argument::any())->will(fn($args) => $args[1]);

        return $file;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $service = new CreatePageFromFileService($this->getFileFactory());
    }

    /**
     * @test
     */
    public function factoryWithInvalidFileShouldReturnNull()
    {
        $service = new CreatePageFromFileService($this->getFileFactory());
        $this->assertNull($service('path/to/file'));
    }

    /**
     * @test
     */
    public function factoryWithValidFileShouldReturnPage()
    {
        $file = $this->getResourceFile();
        $file->getMetadata('title', Argument::any())->willReturn('Page title');
        $file->getMetadata('published', Argument::any())->willReturn('false');

        $service = new CreatePageFromFileService($this->getFileFactory($file->reveal()));
        $page = $service('path/to/file');

        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals('Page title', $page->getTitle());
        $this->assertEquals('my-page', $page->getSlug());
        $this->assertEquals('My content', $page->getBody());
        $this->assertFalse($page->isPublished());
    }

    /**
     * @test
     */
    public function factoryWithFileWithoutMetadataShouldReturnPageWithDefaults()
    {
        $file = $this->getResourceFile();

        $service = new CreatePageFromFileService($this->getFileFactory($file->reveal()));
        $page = $service('path/to/file');

        $this->assertInstanceOf(Page::class, $page);
        $this->assertEquals('Untitled', $page->getTitle());
        $this->assertEquals('my-page', $page->getSlug());
        $this->assertEquals('My content', $page->getBody());
        $this->assertTrue($page->isPublished());
    }

    /**
     * @test
     */
    public function factoryCanHandleNonBooleanPublishedMetadata()
    {
        $file = $this->getResourceFile();
        $file->getMetadata('published', Argument::any())->willReturn('anything-else');

        $service = new CreatePageFromFileService($this->getFileFactory($file->reveal()));
        $page = $service('path/to/file');

        $this->assertInstanceOf(Page::class, $page);
        $this->assertFalse($page->isPublished());
    }
}
