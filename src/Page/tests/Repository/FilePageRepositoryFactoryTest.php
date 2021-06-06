<?php

namespace BlancoHugoTest\Blog\Page\Repository;

use BlancoHugo\Blog\Page\Repository\FilePageRepository;
use BlancoHugo\Blog\Page\Repository\FilePageRepositoryFactory;
use BlancoHugo\Blog\Page\Service\CreatePageFromFileService;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

class FilePageRepositoryFactoryTest extends TestCase
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
        $container->get(CreatePageFromFileService::class)->willReturn($this->getCreateService());
        $container->get('config')->willReturn($config);

        return $container;
    }

    public function getCreateService(): CreatePageFromFileService
    {
        return $this->getMockBuilder(CreatePageFromFileService::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $factory = new FilePageRepositoryFactory();
    }

    /**
     * @test
     */
    public function constructionWithInvalidPathShouldThrowException()
    {
        $config = [
            'resources' => [
                'pages' => [
                    'path' => null,
                    'extension' => 'md'
                ]
            ]
        ];

        $this->expectException(\RuntimeException::class);

        $factory = new FilePageRepositoryFactory();
        $factory($this->getContainer($config)->reveal());
    }

    /**
     * @test
     */
    public function constructionWithInvalidExtensionShouldThrowException()
    {
        $config = [
            'resources' => [
                'pages' => [
                    'path' => $this->root->url() . '/resources',
                    'extension' => null
                ]
            ]
        ];

        $this->expectException(\RuntimeException::class);

        $factory = new FilePageRepositoryFactory();
        $factory($this->getContainer($config)->reveal());
    }

    /**
     * @test
     */
    public function constructionWithEmptyConfigShouldThrowException()
    {
        $this->expectException(\RuntimeException::class);

        $factory = new FilePageRepositoryFactory();
        $factory($this->getContainer()->reveal());
    }

    /**
     * @test
     */
    public function constructionWithValidConfigShouldSucceed()
    {
        $config = [
            'resources' => [
                'pages' => [
                    'path' => $this->root->url() . '/resources',
                    'extension' => 'md'
                ]
            ]
        ];

        $factory = new FilePageRepositoryFactory();
        $this->assertInstanceOf(FilePageRepository::class, $factory($this->getContainer($config)->reveal()));
    }
}
