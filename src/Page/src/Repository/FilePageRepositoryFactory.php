<?php

namespace BlancoHugo\Blog\Page\Repository;

use BlancoHugo\Blog\Page\Service\CreatePageFromFileService;
use Psr\Container\ContainerInterface;

/**
 * Page data file repository factory
 *
 * @package BlancoHugo\Blog\Page\Repository
 */
class FilePageRepositoryFactory
{
    /**
     * Creates an instance of {@see FilePageRepository}
     *
     * @param ContainerInterface $container
     * @return FilePageRepository
     */
    public function __invoke(ContainerInterface $container): FilePageRepository
    {
        $config = $container->get('config');
        $service = $container->get(CreatePageFromFileService::class);

        $resourceConfig = $config['resources'] ?? [];
        $pages = $resourceConfig['pages'] ?? [];

        if (!array_key_exists('path', $pages) || !$pages['path']) {
            throw new \RuntimeException(
                sprintf('"%" is a required config and cannot be empty', 'resources.pages.path')
            );
        }

        if (!array_key_exists('extension', $pages) || !$pages['extension']) {
            throw new \RuntimeException(
                sprintf('"%" is a required config and cannot be empty', 'resources.pages.extension')
            );
        }

        return new FilePageRepository($service, $pages['path'], $pages['extension']);
    }
}
