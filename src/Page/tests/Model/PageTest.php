<?php

namespace BlancoHugoTest\Blog\Page\Model;

use BlancoHugo\Blog\Page\Model\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $page = new Page('Some title', 'my-slug', '<p>My content</p>');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function dataCanBeSetted()
    {
        $page = new Page('Some title', 'my-slug', '<p>My content</p>');
        $page->setPublished(false);

        return $page;
    }

    /**
     * @test
     * @depends dataCanBeSetted
     */
    public function dataCanBeRetrieved(Page $page)
    {
        $this->assertEquals('Some title', $page->getTitle());
        $this->assertEquals('my-slug', $page->getSlug());
        $this->assertEquals('<p>My content</p>', $page->getBody());
        $this->assertFalse($page->isPublished());
    }

    /**
     * @test
     */
    public function newPagesShouldBePublished()
    {
        $page = new Page('Some title', 'my-slug', '<p>My content</p>');
        $this->assertTrue($page->isPublished());
    }
}
