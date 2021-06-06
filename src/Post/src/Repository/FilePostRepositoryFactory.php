<?php

namespace BlancoHugo\Blog\Post\Repository;

use BlancoHugo\Blog\Post\Service\CreatePostFromFileService;
use Psr\Container\ContainerInterface;

/**
 * Post data file repository factory
 *
 * @package BlancoHugo\Blog\Post\Repository
 */
class FilePostRepositoryFactory
{
    /**
     * Creates an instance of {@see FilePostRepository}
     *
     * @param ContainerInterface $container
     * @return FilePostRepository
     */
    public function __invoke(ContainerInterface $container): FilePostRepository
    {
        $config = $container->get('config');
        $service = $container->get(CreatePostFromFileService::class);

        $resourceConfig = $config['resources'] ?? [];
        $posts = $resourceConfig['posts'] ?? [];

        if (!array_key_exists('path', $posts) || !$posts['path']) {
            throw new \RuntimeException(
                sprintf('"%" is a required config and cannot be empty', 'resources.posts.path')
            );
        }

        if (!array_key_exists('extension', $posts) || !$posts['extension']) {
            throw new \RuntimeException(
                sprintf('"%" is a required config and cannot be empty', 'resources.posts.extension')
            );
        }

        return new FilePostRepository($service, $posts['path'], $posts['extension']);
    }
}
