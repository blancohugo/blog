<?php

namespace BlancoHugoTest\Blog\Common\Repository;

use BlancoHugo\Blog\Common\Repository\FileRepository;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileRepositoryTest extends TestCase
{
    private $root;

    public function setUp(): void
    {
        $hierarchy = [
            'resources' => [
                'with-content.md' => "Title\r\nLine1\r\nLine2\r\nLine3",
                'another-with-content.md' => "Title\r\nLine1",
                'empty-content.md' => '',
                'file-with-different-extension.txt' => 'text file'
            ]
        ];

        $this->root = vfsStream::setup('root', 755, $hierarchy);
    }

    private function getFileRepository(string $directory, string $extension): FileRepository
    {
        return new class ($directory, $extension) extends FileRepository {
        };
    }

    /**
     * @test
     */
    public function objectCannotBeConstructedWithInvalidPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $repository = $this->getFileRepository('invalid/directory', 'md');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithValidPath()
    {
        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        return $repository;
    }

    /**
     * @test
     */
    public function existentFileCanBeLoaded()
    {
        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertEquals("Title\r\nLine1\r\nLine2\r\nLine3", $repository->load('with-content'));
        $this->assertEquals('', $repository->load('empty-content'));
    }

    /**
     * @test
     */
    public function fileShouldNotBeLoadedTwice()
    {
        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertEquals('', $repository->load('empty-content'));

        $this->root->getChild('resources')->getChild('empty-content.md')->setContent('changed content');
        $this->assertEquals('', $repository->load('empty-content'));
    }

    /**
     * @test
     */
    public function inexistentFileShouldReturnNullOnLoad()
    {
        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertNull($repository->load('inexistent-file'));
    }

    /**
     * @test
     */
    public function onlyFilesWithTheGivenExtensionShouldBeLoaded()
    {
        $expectedArray = [
            'file-with-different-extension' => 'text file'
        ];

        $repository = $this->getFileRepository($this->root->url() . '/resources', 'txt');
        $this->assertEquals($expectedArray, $repository->loadAll());
    }

    /**
     * @test
     */
    public function loadAllFilesShouldOrderResultsByDescendant()
    {
        $expectedArray = [
            'with-content' => "Title\r\nLine1\r\nLine2\r\nLine3",
            'empty-content' => '',
            'another-with-content' => "Title\r\nLine1",
        ];

        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertSame($expectedArray, $repository->loadAll());
    }

    /**
     * @test
     */
    public function loadedFilesCanBeFiltered()
    {
        $expectedArray = [
            'with-content' => "Title\r\nLine1\r\nLine2\r\nLine3",
        ];

        $hasLine2 = fn($content) => (strpos($content, 'Line2') !== false);

        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertEquals($expectedArray, $repository->filter($hasLine2));
    }

    /**
     * @test
     */
    public function filteredLoadedFilesCanBeLimited()
    {
        $expectedArray = [
            'with-content' => "Title\r\nLine1\r\nLine2\r\nLine3",
        ];

        $hasLine1 = fn($content) => (strpos($content, 'Line1') !== false);

        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertEquals($expectedArray, $repository->filter($hasLine1, 1));
    }

    /**
     * @test
     */
    public function loadAllFilesCanBeLimited()
    {
        $expectedArray = [
            'with-content' => "Title\r\nLine1\r\nLine2\r\nLine3",
        ];

        $repository = $this->getFileRepository($this->root->url() . '/resources', 'md');
        $this->assertEquals($expectedArray, $repository->loadAll(1));
    }
}
