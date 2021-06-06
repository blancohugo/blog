<?php

namespace BlancoHugoTest\Blog\File;

use BlancoHugo\Blog\File\ResourceFile;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ResourceFileTest extends TestCase
{
    private $root;

    public function setUp(): void
    {
        $hierarchy = [
            'resources' => [
                'my-content.md' => "@title Title\r\n@author Me\r\n\r\nword1\r\nword2\r\nword3\r\nword4\r\n",
                'my-empty-content.md' => ""
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
        $file = new ResourceFile('invalid/path/to/file');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructedWithValidPath()
    {
        $file = new ResourceFile($this->root->url() . '/resources/my-content.md');
        return $file;
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithValidPath
     */
    public function contentShouldBeTrimmed(ResourceFile $file)
    {
        $this->assertEquals("word1\r\nword2\r\nword3\r\nword4", $file->getContent());
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithValidPath
     */
    public function contentMetadataCanBeRetrieved(ResourceFile $file)
    {
        $this->assertEquals('Title', $file->getMetadata('title'));
        $this->assertEquals('Me', $file->getMetadata('author'));
    }

    /**
     * @test
     */
    public function emptyContentMetadataCanBeRetrieved()
    {
        $file = new ResourceFile($this->root->url() . '/resources/my-empty-content.md');
        $this->assertEquals('default-title', $file->getMetadata('title', 'default-title'));
        $this->assertNull($file->getMetadata('author'));
        $this->assertNull($file->getContent());
    }

    /**
     * @test
     * @depends objectCanBeConstructedWithValidPath
     */
    public function inexistentContentMetadataShouldReturnDefaultValue(ResourceFile $file)
    {
        $this->assertNull($file->getMetadata('inexistent-index'));
        $this->assertEquals('my-default-value', $file->getMetadata('special-slug', 'my-default-value'));
        $this->assertEquals(20, $file->getMetadata('time-to-read', 20));
    }
}
