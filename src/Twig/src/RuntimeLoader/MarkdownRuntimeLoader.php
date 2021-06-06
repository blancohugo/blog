<?php

namespace BlancoHugo\Blog\Twig\RuntimeLoader;

use Twig\Extra\Markdown\MarkdownInterface;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

/**
 * Twig markdown runtime loader
 *
 * @package BlancoHugo\Blog\Twig\RuntimeLoader
 */
class MarkdownRuntimeLoader implements RuntimeLoaderInterface
{
    /**
     * Markdown parser
     *
     * @var MarkdownInterface
     */
    private $parser;

    /**
     * Class constructor
     *
     * @param MarkdownInterface $parser
     */
    public function __construct(MarkdownInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritDoc}
     */
    public function load($class)
    {
        if (MarkdownRuntime::class === $class) {
            return new MarkdownRuntime($this->parser);
        }
    }
}
