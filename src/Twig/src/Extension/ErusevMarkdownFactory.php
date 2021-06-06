<?php

namespace BlancoHugo\Blog\Twig\Extension;

use Parsedown;
use Psr\Container\ContainerInterface;
use Twig\Extra\Markdown\ErusevMarkdown;

/**
 * Twig markdown converter factory
 */
class ErusevMarkdownFactory
{
    /**
     * Creates a new {@see ErusevMarkdown} instance
     *
     * @param ContainerInterface $container
     * @return ErusevMarkdown
     */
    public function __invoke(ContainerInterface $container): ErusevMarkdown
    {
        $parsedown = $container->get(Parsedown::class);

        if (!$parsedown) {
            throw new \RuntimeException(sprintf(
                'Container couldn\'t get an instance of %s',
                Parsedown::class
            ));
        }

        $parsedown->setBreaksEnabled(true);
        return new ErusevMarkdown($parsedown);
    }
}
