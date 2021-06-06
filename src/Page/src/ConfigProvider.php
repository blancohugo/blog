<?php

namespace BlancoHugo\Blog\Page;

/**
 * The configuration provider for the Page module
 *
 * @package BlancoHugo\Blog\Page
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
                Repository\PageRepositoryInterface::class => Repository\FilePageRepository::class
            ],
            'factories' => [
                Repository\FilePageRepository::class => Repository\FilePageRepositoryFactory::class
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
                'name'            => 'page.detail',
                'path'            => '/page/{page:[a-z0-9\-]+}',
                'middleware'      => Handler\GetPageDetailHandler::class,
                'allowed_methods' => ['GET'],
            ]
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
                'page' => [__DIR__ . '/../templates/page'],
            ],
        ];
    }
}
