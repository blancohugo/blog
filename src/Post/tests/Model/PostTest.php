<?php

namespace BlancoHugoTest\Blog\Post\Model;

use BlancoHugo\Blog\Post\Model\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $post = new Post('Some title', 'My summary', 'my-slug', '<p>My content</p>');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function dataCanBeSetted()
    {
        $post = new Post('Some title', 'My summary', 'my-slug', '<p>My content</p>');
        $post->setAuthor('Me');
        $post->setDate(new \DateTimeImmutable('2020-01-01'));
        $post->setPublished(false);

        return $post;
    }

    /**
     * @test
     * @depends dataCanBeSetted
     */
    public function dataCanBeRetrieved(Post $post)
    {
        $this->assertEquals('Some title', $post->getTitle());
        $this->assertEquals('My summary', $post->getSummary());
        $this->assertEquals('my-slug', $post->getSlug());
        $this->assertEquals('<p>My content</p>', $post->getBody());
        $this->assertEquals('Me', $post->getAuthor());
        $this->assertEquals(new \DateTimeImmutable('2020-01-01'), $post->getDate());
        $this->assertFalse($post->isPublished());
    }

    /**
     * @test
     */
    public function newPostShouldBePublished()
    {
        $post = new Post('Some title', 'My summary', 'my-slug', '<p>My content</p>');
        $this->assertTrue($post->isPublished());
    }

    /**
     * @test
     */
    public function newPostShouldHavePublishedDate()
    {
        $post = new Post('Some title', 'My summary', 'my-slug', '<p>My content</p>');
        $this->assertInstanceOf(\DateTimeInterface::class, $post->getDate());
    }
}
