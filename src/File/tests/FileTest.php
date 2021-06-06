<?php

namespace BlancoHugoTest\Blog\File;

use BlancoHugo\Blog\File\File;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private $root;

    public function setUp(): void
    {
        $hierarchy = [
            'files' => [
                'my-file.txt' => "My content\r\nword1\r\nword2\r\nword3\r\nword4   ",
                'my-empty-file.txt' => ""
            ]
        ];

        $this->root = vfsStream::setup('root', 755, $hierarchy);
    }

    /**
     * @test
     */
    public function objectCannotBeConstructedWithInvalidPath()
    {
        $this->expectException(\InvalidArgumentException::class);
        $file = new File('invalid/path/to/file');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithValidPath()
    {
        $file = new File($this->root->url() . '/files/my-file.txt');
        return $file;
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithValidPath
     */
    public function contentShouldBeTrimmed(File $file)
    {
        $this->assertEquals("My content\r\nword1\r\nword2\r\nword3\r\nword4", $file->getContent());
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithValidPath
     */
    public function contentShouldNotBeLoadedTwice(File $file)
    {
        $this->assertEquals("My content\r\nword1\r\nword2\r\nword3\r\nword4", $file->getContent());

        $this->root->getChild('files')->getChild('my-file.txt')->setContent('changed content');
        $this->assertEquals("My content\r\nword1\r\nword2\r\nword3\r\nword4", $file->getContent());
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithValidPath
     */
    public function fileMetadataCanBeRetrieved(File $file)
    {
        $this->assertEquals($this->root->url() . '/files/my-file.txt', $file->getPath());
        $this->assertEquals('my-file.txt', $file->getBasename());
        $this->assertEquals('my-file', $file->getFilename());
    }
}
