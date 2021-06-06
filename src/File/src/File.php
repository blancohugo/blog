<?php

namespace BlancoHugo\Blog\File;

class File
{
    /**
     * File path
     *
     * @var string
     */
    protected $path;

    /**
     * File content
     *
     * @var string|null
     */
    protected $content;

    /**
     * Flag for content loaded
     *
     * @var boolean
     */
    protected $contentLoaded;

    /**
     * Class construtor
     *
     * @param string $path
     * @throws \InvalidArgumentException If the file path is not readable
     */
    public function __construct(string $path)
    {
        if (!is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a readable path', $path));
        }

        $this->path = $path;
        $this->contentLoaded = false;
    }

    /**
     * Returns file path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Returns the file basename
     *
     * @return string
     */
    public function getBasename(): string
    {
        $pathParts = pathinfo($this->path);
        return $pathParts['basename'];
    }

    /**
     * Returns the file name
     *
     * @return string
     */
    public function getFilename(): string
    {
        $pathParts = pathinfo($this->path);
        return $pathParts['filename'];
    }

    /**
     * Loads when needed and returns the file content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        if ($this->contentLoaded) {
            return $this->content;
        }

        $content = file_get_contents($this->path);
        $this->content = trim($content);
        $this->contentLoaded = true;

        return $this->content;
    }
}
