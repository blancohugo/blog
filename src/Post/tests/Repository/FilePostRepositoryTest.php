<?php

namespace BlancoHugoTest\Blog\Post\Repository;

use BlancoHugo\Blog\Post\Model\Post;
use BlancoHugo\Blog\Post\Repository\FilePostRepository;
use BlancoHugo\Blog\Post\Service\CreatePostFromFileService;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class FilePostRepositoryTest extends TestCase
{
    use ProphecyTrait;

    public function setUp(): void
    {
        $hierarchy = [
            'resources' => [
                'first-post.md' => "Title\r\nLine1\r\nLine2\r\nLine3",
                'second-post.md' => "Title\r\nLine1",
                'third-post.md' => '',
                'note.txt' => 'text file'
            ]
        ];

        $this->root = vfsStream::setup('root', 755, $hierarchy);
        $this->posts = [
            'first-post' => new Post('First post', 'Summary', 'first-post', 'First post content'),
            'second-post' => new Post('Second post', 'Summary', 'second-post', 'Second post content'),
            'third-post' => new Post('Third post', 'Summary', 'third-post', 'Third post content')
        ];
    }

    public function getCreateService(): ObjectProphecy
    {
        $baseUrl = $this->root->url() . '/resources';

        $prophecy = $this->prophesize(CreatePostFromFileService::class);
        $prophecy->__invoke("{$baseUrl}/first-post.md")->willReturn($this->posts['first-post']);
        $prophecy->__invoke("{$baseUrl}/second-post.md")->willReturn($this->posts['second-post']);
        $prophecy->__invoke("{$baseUrl}/third-post.md")->willReturn($this->posts['third-post']);

        return $prophecy;
    }

    /**
     * @test
     */
    public function objectCannotBeConstructedWithInvalidPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $repository = new FilePostRepository(
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
        $repository = new FilePostRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );
        return $repository;
    }

    /**
     * @test
     */
    public function postShouldNotBeLoadedTwice()
    {
        $createService = $this->getCreateService();
        $repository = new FilePostRepository(
            $createService->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $repository->load('first-post');
        $repository->load('first-post');

        $createService->__invoke(Argument::any())->shouldBeCalledTimes(1);
    }

    /**
     * @test
     */
    public function postCanBeFoundBySlug()
    {
        $repository = new FilePostRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $this->assertInstanceOf(Post::class, $repository->findBySlug('first-post'));
        $this->assertInstanceOf(Post::class, $repository->findBySlug('second-post'));
        $this->assertInstanceOf(Post::class, $repository->findBySlug('third-post'));
    }

    /**
     * @test
     */
    public function inexistentSlugShouldReturnNull()
    {
        $repository = new FilePostRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $this->assertNull($repository->findBySlug('inexistent-slug'));
    }

    /**
     * @test
     */
    public function findAllCanReturnAllPosts()
    {
        $repository = new FilePostRepository(
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
        $repository = new FilePostRepository(
            $this->getCreateService()->reveal(),
            $this->root->url() . '/resources',
            'md'
        );

        $expectedArray = ['third-post' => $this->posts['third-post']];
        $this->assertSame($expectedArray, $repository->findAll(1));
    }
}
