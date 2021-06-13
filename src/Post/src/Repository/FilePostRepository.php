<?php

namespace BlancoHugo\Blog\Post\Repository;

use BlancoHugo\Blog\Common\Repository\FileRepository;
use BlancoHugo\Blog\Post\Model\Post;
use BlancoHugo\Blog\Post\Service\CreatePostFromFileService;

/**
 * Post data repository with files
 *
 * @package BlancoHugo\Blog\Post\Repository
 */
class FilePostRepository extends FileRepository implements PostRepositoryInterface
{
    /**
     * Service to create posts from files
     *
     * @var CreatePostFromFileService
     */
    private $createService;

    /**
     * Class constructor
     *
     * @param CreatePostFromFileService $createService
     * @param string $path
     * @param string $extension
     */
    public function __construct(CreatePostFromFileService $createService, string $path, string $extension)
    {
        parent::__construct($path, $extension);
        $this->createService = $createService;
    }

    /**
     * {@inheritDoc}
     */
    public function load(string $filename)
    {
        if (!$this->exists($filename)) {
            return null;
        }

        if ($this->isLoaded($filename)) {
            return $this->loadedFiles[$filename];
        }

        $post = ($this->createService)($this->makePath($filename));
        $this->loadedFiles[$filename] = $post;

        return $post;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(int $quantity = null): array
    {
        return $this->loadAll($quantity);
    }

    /**
     * {@inheritDoc}
     */
    public function findPublished(int $quantity = null): array
    {
        return $this->filter(fn(Post $post) => $post->isPublished(), $quantity);
    }

    /**
     * {@inheritDoc}
     */
    public function findBySlug(string $slug): ?Post
    {
        return $this->load($slug);
    }
}
