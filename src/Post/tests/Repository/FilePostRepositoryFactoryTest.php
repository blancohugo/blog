<?php

namespace BlancoHugoTest\Blog\Post\Repository;

use BlancoHugo\Blog\Post\Repository\FilePostRepository;
use BlancoHugo\Blog\Post\Repository\FilePostRepositoryFactory;
use BlancoHugo\Blog\Post\Service\CreatePostFromFileService;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

class FilePostRepositoryFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $hierarchy = [
            'resources' => [
                'file-with-content.md' => "Title\r\nLine1\r\nLine2\r\nLine3",
            ]
        ];

        $this->root = vfsStream::setup('root', 755, $hierarchy);
    }

    public function getContainer(array $config = []): ObjectProphecy
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get(CreatePostFromFileService::class)->willReturn($this->getCreateService());
        $container->get('config')->willReturn($config);

        return $container;
    }

    public function getCreateService(): MockObject
    {
        return $this->getMockBuilder(CreatePostFromFileService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $factory = new FilePostRepositoryFactory();
    }

    /**
     * @test
     */
    public function constructionWithInvalidPathShouldThrowException()
    {
        $config = [
            'resources' => [
                'posts' => [
                    'path' => null,
                    'extension' => 'md'
                ]
            ]
        ];

        $this->expectException(\RuntimeException::class);

        $factory = new FilePostRepositoryFactory();
        $factory($this->getContainer($config)->reveal());
    }

    /**
     * @test
     */
    public function constructionWithInvalidExtensionShouldThrowException()
    {
        $config = [
            'resources' => [
                'posts' => [
                    'path' => $this->root->url() . '/resources',
                    'extension' => null
                ]
            ]
        ];

        $this->expectException(\RuntimeException::class);

        $factory = new FilePostRepositoryFactory();
        $factory($this->getContainer($config)->reveal());
    }

    /**
     * @test
     */
    public function constructionWithEmptyConfigShouldThrowException()
    {
        $this->expectException(\RuntimeException::class);

        $factory = new FilePostRepositoryFactory();
        $factory($this->getContainer()->reveal());
    }

    /**
     * @test
     */
    public function constructionWithValidConfigShouldSucceed()
    {
        $config = [
            'resources' => [
                'posts' => [
                    'path' => $this->root->url() . '/resources',
                    'extension' => 'md'
                ]
            ]
        ];

        $factory = new FilePostRepositoryFactory();
        $this->assertInstanceOf(FilePostRepository::class, $factory($this->getContainer($config)->reveal()));
    }
}
