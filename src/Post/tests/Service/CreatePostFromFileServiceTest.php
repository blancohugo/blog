<?php

namespace BlancoHugoTest\Blog\Post\Service;

use BlancoHugo\Blog\File\ResourceFile;
use BlancoHugo\Blog\File\ResourceFileFactory;
use BlancoHugo\Blog\Post\Model\Post;
use BlancoHugo\Blog\Post\Service\CreatePostFromFileService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class CreatePostFromFileServiceTest extends TestCase
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
        $file->getFilename()->willReturn('my-post');
        $file->getContent()->willReturn('My content');
        $file->getMetadata('title', Argument::any())->will(fn($args) => $args[1]);
        $file->getMetadata('summary', Argument::any())->will(fn($args) => $args[1]);
        $file->getMetadata('author', Argument::any())->will(fn($args) => $args[1]);
        $file->getMetadata('date', Argument::any())->will(fn($args) => $args[1]);
        $file->getMetadata('published', Argument::any())->will(fn($args) => $args[1]);

        return $file;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $service = new CreatePostFromFileService($this->getFileFactory());
    }

    /**
     * @test
     */
    public function factoryWithInvalidFileShouldReturnNull()
    {
        $service = new CreatePostFromFileService($this->getFileFactory());
        $this->assertNull($service('path/to/file'));
    }

    /**
     * @test
     */
    public function factoryWithValidFileShouldReturnPost()
    {
        $file = $this->getResourceFile();
        $file->getMetadata('title', Argument::any())->willReturn('Post title');
        $file->getMetadata('summary', Argument::any())->willReturn('My summary');
        $file->getMetadata('author', Argument::any())->willReturn('Me');
        $file->getMetadata('date', Argument::any())->willReturn('2020-01-01');
        $file->getMetadata('published', Argument::any())->willReturn('false');

        $service = new CreatePostFromFileService($this->getFileFactory($file->reveal()));
        $post = $service('path/to/file');

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Post title', $post->getTitle());
        $this->assertEquals('My summary', $post->getSummary());
        $this->assertEquals('my-post', $post->getSlug());
        $this->assertEquals('My content', $post->getBody());
        $this->assertEquals('Me', $post->getAuthor());
        $this->assertEquals(new \DateTimeImmutable('2020-01-01'), $post->getDate());
        $this->assertFalse($post->isPublished());
    }

    /**
     * @test
     */
    public function factoryWithFileWithoutMetadataShouldReturnPostWithDefaults()
    {
        $file = $this->getResourceFile();

        $service = new CreatePostFromFileService($this->getFileFactory($file->reveal()));
        $post = $service('path/to/file');

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Untitled', $post->getTitle());
        $this->assertEquals('No summary', $post->getSummary());
        $this->assertEquals('my-post', $post->getSlug());
        $this->assertEquals('My content', $post->getBody());
        $this->assertEquals('Unknown', $post->getAuthor());
        $this->assertInstanceOf(\DateTimeInterface::class, $post->getDate());
        $this->assertTrue($post->isPublished());
    }

    /**
     * @test
     */
    public function factoryCanHandleNonBooleanPublishedMetadata()
    {
        $file = $this->getResourceFile();
        $file->getMetadata('published', Argument::any())->willReturn('anything-else');

        $service = new CreatePostFromFileService($this->getFileFactory($file->reveal()));
        $post = $service('path/to/file');

        $this->assertInstanceOf(Post::class, $post);
        $this->assertFalse($post->isPublished());
    }
}
