<?php

namespace BlancoHugo\Blog\Post\Service;

use BlancoHugo\Blog\File\ResourceFileFactory;
use BlancoHugo\Blog\Post\Model\Post;

/**
 * Service to create posts from resource files
 *
 * @package BlancoHugo\Blog\Post\Service
 */
class CreatePostFromFileService
{
    /**
     * Resource file factory
     *
     * @var ResourceFileFactory
     */
    private $fileFactory;

    /**
     * Class constructor
     *
     * @param ResourceFileFactory $fileFactory
     */
    public function __construct(ResourceFileFactory $fileFactory)
    {
        $this->fileFactory = $fileFactory;
    }

    /**
     * Executes the service
     *
     * @param string $path
     * @return Post|null
     */
    public function __invoke(string $path): ?Post
    {
        if (!$file = ($this->fileFactory)($path)) {
            return null;
        }

        $slug = $file->getFilename();
        $content = $file->getContent();

        $title = $file->getMetadata('title', 'Untitled');
        $summary = $file->getMetadata('summary', 'No summary');
        $author = $file->getMetadata('author', 'Unknown');
        $date = $file->getMetadata('date', 'now');
        $published = $file->getMetadata('published', 'true');

        $post = new Post($title, $summary, $slug, $content);
        $post->setAuthor($author);
        $post->setDate(new \DateTimeImmutable($date));
        $post->setPublished($published === 'true' ? true : false);

        return $post;
    }
}
