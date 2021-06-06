<?php

namespace BlancoHugo\Blog\Post;

/**
 * The configuration provider for the Post module
 *
 * @package BlancoHugo\Blog\Post
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'routes'       => $this->getRoutes(),
            'templates'    => $this->getTemplates()
        ];
    }

    /**
     * Returns the dependencies configuration
     *
     * @return array
     */
    private function getDependencies(): array
    {
        return [
            'aliases' => [
                Repository\PostRepositoryInterface::class => Repository\FilePostRepository::class
            ],
            'factories' => [
                Repository\FilePostRepository::class => Repository\FilePostRepositoryFactory::class
            ]
        ];
    }

    /**
     * Returns the routes configuration
     *
     * @return array
     */
    private function getRoutes(): array
    {
        return [
            [
                'name'            => 'post.detail',
                'path'            => '/post/{post:[a-z0-9\-]+}',
                'middleware'      => Handler\GetPostDetailHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name'            => 'post.archive',
                'path'            => '/archive',
                'middleware'      => Handler\GetPostArchiveHandler::class,
                'allowed_methods' => ['GET'],
            ],
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    private function getTemplates(): array
    {
        return [
            'paths' => [
                'post' => [__DIR__ . '/../templates/post'],
            ],
        ];
    }
}
