<?php

namespace BlancoHugoTest\Blog\Post\Service;

use BlancoHugo\Blog\Post\Model\Post;
use BlancoHugo\Blog\Post\Service\GroupPostsByDateService;
use PHPUnit\Framework\TestCase;

class GroupPostsByDateServiceTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $service = new GroupPostsByDateService();
    }

    /**
     * @test
     */
    public function serviceCanHandleEmptyList()
    {
        $service = new GroupPostsByDateService();
        $this->assertEquals([], $service([]));
    }

    /**
     * @test
     */
    public function serviceCanHandleListWithInvalidPosts()
    {
        $posts = [5, new \DateTime(), 'string'];

        $service = new GroupPostsByDateService();
        $this->assertEquals([], $service($posts));
    }

    /**
     * @test
     */
    public function validPostsCanBeGrouped()
    {
        $post1 = (new Post('Title1', 'Summary', 'post-1', 'My content'))->setDate(new \DateTimeImmutable('2020-01-01'));
        $post2 = (new Post('Title2', 'Summary', 'post-2', 'My content'))->setDate(new \DateTimeImmutable('2020-01-01'));
        $post3 = (new Post('Title3', 'Summary', 'post-3', 'My content'))->setDate(new \DateTimeImmutable('2020-01-02'));
        $post4 = (new Post('Title4', 'Summary', 'post-4', 'My content'))->setDate(new \DateTimeImmutable('2018-01-01'));

        $posts = [
            $post1,
            $post2,
            $post3,
            $post4
        ];

        $expectedArray = [
            '2020-01' => [
                $post1,
                $post2,
                $post3,
            ],
            '2018-01' => [
                $post4
            ]
        ];

        $service = new GroupPostsByDateService();
        $this->assertEquals($expectedArray, $service($posts));
    }
}
