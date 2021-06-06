<?php

namespace BlancoHugo\Blog\Twig;

use BlancoHugo\Blog\Twig\Middleware\TwigProvidedGlobalsMiddleware;
use BlancoHugo\Blog\Twig\Middleware\TwigProvidedGlobalsMiddlewareFactory;
use Twig\Extra\Markdown;

/**
 * The configuration provider for the Twig module
 *
 * @package BlancoHugo\Blog\Twig
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
            'twig'         => $this->getTwigConfig()
        ];
    }

    /**
     * Returns the dependencies configuration
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'aliases' => [
                Markdown\MarkdownInterface::class => Markdown\ErusevMarkdown::class
            ],
            'factories' => [
                Markdown\ErusevMarkdown::class => Extension\ErusevMarkdownFactory::class,
                TwigProvidedGlobalsMiddleware::class => TwigProvidedGlobalsMiddlewareFactory::class
            ]
        ];
    }

    /**
     * Returns the twig configuration
     *
     * @return array
     */
    public function getTwigConfig(): array
    {
        return [
            'extensions' => [
                Markdown\MarkdownExtension::class
            ],
            'runtime_loaders' => [
                RuntimeLoader\MarkdownRuntimeLoader::class
            ]
        ];
    }
}
