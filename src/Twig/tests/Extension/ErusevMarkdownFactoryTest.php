<?php

namespace BlancoHugoTest\Blog\Twig\Extension;

use BlancoHugo\Blog\Twig\Extension\ErusevMarkdownFactory;
use Parsedown;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Twig\Extra\Markdown\ErusevMarkdown;

class ErusevMarkdownFactoryTest extends TestCase
{
    use ProphecyTrait;

    public function getContainer(): ObjectProphecy
    {
        return $this->prophesize(ContainerInterface::class);
    }

    public function getParsedown(): Parsedown
    {
        return $this->getMockBuilder(Parsedown::class)
            ->getMock();
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $factory = new ErusevMarkdownFactory();
        return $factory;
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateInstanceWithExistentDependencies()
    {
        $container = $this->getContainer();
        $container->get(Parsedown::class)->willReturn($this->getParsedown());

        $factory = new ErusevMarkdownFactory();
        $markdown = $factory($container->reveal());

        $this->assertInstanceOf(ErusevMarkdown::class, $markdown);
    }

    /**
     * @test
     */
    public function instanceCreationWithInexistentDependenciesShouldThrowException()
    {
        $container = $this->getContainer();
        $container->get(Parsedown::class)->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $factory = new ErusevMarkdownFactory();
        $markdown = $factory($container->reveal());
    }
}
