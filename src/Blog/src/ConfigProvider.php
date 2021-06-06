<?php

namespace BlancoHugo\Blog;

/**
 * The configuration provider for the App module
 *
 * @package BlancoHugo\Blog
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
            'routes' => $this->getRoutes(),
            'templates' => $this->getTemplates(),
        ];
    }

    /**
     * Returns the routes configuration
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return [
            [
                'name'            => 'home',
                'path'            => '/',
                'middleware'      => Handler\HomePageHandler::class,
                'allowed_methods' => ['GET'],
            ]
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => [__DIR__ . '/../templates/app'],
                'error'  => [__DIR__ . '/../templates/error'],
                'layout' => [__DIR__ . '/../templates/layout'],
            ],
        ];
    }
}
