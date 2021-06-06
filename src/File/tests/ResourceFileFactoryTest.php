<?php

namespace BlancoHugoTest\Blog\File;

use BlancoHugo\Blog\File\ResourceFile;
use BlancoHugo\Blog\File\ResourceFileFactory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ResourceFileFactoryTest extends TestCase
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
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $factory = new ResourceFileFactory();
        return $factory;
    }

    /**
     * @test
     */
    public function canCreateShouldReturnTrueWithValidFile()
    {
        $factory = new ResourceFileFactory();
        $this->assertTrue($factory->canCreate($this->root->url() . '/resources/my-content.md'));
    }

    /**
     * @test
     */
    public function canCreateShouldReturnFalseWithInvalidValid()
    {
        $factory = new ResourceFileFactory();
        $this->assertFalse($factory->canCreate('invalid/path/to/file'));
    }

    /**
     * @test
     */
    public function shouldBeAbleToCreateInstanceWithValidFile()
    {
        $factory = new ResourceFileFactory();
        $resourceFile = $factory($this->root->url() . '/resources/my-content.md');

        $this->assertInstanceOf(ResourceFile::class, $resourceFile);
    }

    /**
     * @test
     */
    public function shouldNotBeAbleToCreateInstanceWithInvalidFile()
    {
        $factory = new ResourceFileFactory();
        $resourceFile = $factory('invalid/path/to/file');

        $this->assertNull($resourceFile);
    }
}
