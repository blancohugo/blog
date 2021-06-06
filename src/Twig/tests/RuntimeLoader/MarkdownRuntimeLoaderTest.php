<?php

namespace BlancoHugoTest\Blog\Twig\RuntimeLoader;

use BlancoHugo\Blog\Twig\RuntimeLoader\MarkdownRuntimeLoader;
use PHPUnit\Framework\TestCase;
use Twig\Extra\Markdown\MarkdownInterface;
use Twig\Extra\Markdown\MarkdownRuntime;

class MarkdownRuntimeLoaderTest extends TestCase
{
    public function getMarkdownParser(): MarkdownInterface
    {
        return $this->getMockBuilder(MarkdownInterface::class)
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $loader = new MarkdownRuntimeLoader($this->getMarkdownParser());
        return $loader;
    }

    /**
     * @test
     */
    public function loaderShouldBeAbleToLoadOnlyMarkdownRuntimeClasses()
    {
        $loader = new MarkdownRuntimeLoader($this->getMarkdownParser());

        $this->assertNull($loader->load('\Anything\Else'));
        $this->assertInstanceOf(MarkdownRuntime::class, $loader->load(MarkdownRuntime::class));
    }
}
