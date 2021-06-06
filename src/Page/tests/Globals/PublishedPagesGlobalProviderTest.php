<?php

namespace BlancoHugoTest\Blog\Page\Globals;

use BlancoHugo\Blog\Page\Globals\PublishedPagesGlobalProvider;
use BlancoHugo\Blog\Page\Repository\PageRepositoryInterface;
use PHPUnit\Framework\TestCase;

class PublishedPagesGlobalProviderTest extends TestCase
{
    public function getRepository(): PageRepositoryInterface
    {
        return $this->getMockBuilder(PageRepositoryInterface::class)
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $provider = new PublishedPagesGlobalProvider($this->getRepository());
    }

    /**
     * @test
     */
    public function dataCanBeRetrieved()
    {
        $published = [];

        $repository = $this->getRepository();
        $repository->method('findPublished')->willReturn($published);

        $provider = new PublishedPagesGlobalProvider($this->getRepository());
        $this->assertEquals($published, $provider());
    }
}
