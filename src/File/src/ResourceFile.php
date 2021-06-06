<?php

namespace BlancoHugo\Blog\File;

/**
 * Class to extract the content and metadata from a resource file
 *
 * @package BlancoHugo\Blog\File
 */
class ResourceFile extends File
{
    /**
     * Content metadata array
     *
     * @var array
     */
    protected $metadata;

    /**
     * Class construtor
     *
     * @param string $path
     * @throws \InvalidArgumentException If the file path is not readable
     */
    public function __construct(string $path)
    {
        parent::__construct($path);
        $this->metadata = [];
    }

    /**
     * Loads file content and content metadata
     *
     * @return void
     */
    protected function load()
    {
        if ($this->contentLoaded) {
            return;
        }

        $content = file_get_contents($this->path);

        if (!$content) {
            $this->contentLoaded = true;
            return;
        }

        preg_match_all('/\@([a-z]+)\s([^\r\n\@]+)/i', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $fullMatch = $match[0];
            $variable = $match[1];
            $value = $match[2];

            $content = str_replace($fullMatch, '', $content);
            $this->metadata[$variable] = $value;
        }

        $this->content = trim($content, "\r\n");
        $this->contentLoaded = true;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent(): ?string
    {
        $this->load();
        return $this->content;
    }

    /**
     * Returns a content metadata by a given index
     *
     * @param string $index
     * @param mixed $default
     * @return mixed
     */
    public function getMetadata(string $index, $default = null)
    {
        $this->load();

        if (!array_key_exists($index, $this->metadata)) {
            return $default;
        }

        return $this->metadata[$index];
    }
}
