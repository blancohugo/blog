<?php

namespace BlancoHugo\Blog\Twig\Middleware;

use Psr\Container\ContainerInterface;
use Twig\Environment;

/**
 * Factory to create middlewares to process globals provided by callable
 *
 * @package BlancoHugo\Blog\Twig\Middleware
 */
class TwigProvidedGlobalsMiddlewareFactory
{
    /**
     * Creates a new {@see TwigProvidedGlobalsMiddleware} instance
     *
     * @param ContainerInterface $container
     * @return TwigProvidedGlobalsMiddleware
     * @throws \RuntimeException If a config global name or value is not valid
     */
    public function __invoke(ContainerInterface $container): TwigProvidedGlobalsMiddleware
    {
        $environment = $container->get(Environment::class);
        $config = $container->get('config');

        $twigConfig = $config['twig'] ?? [];
        $globals = $twigConfig['provided_globals'] ?? [];

        $providers = [];

        foreach ($globals as $name => $global) {
            if (!is_string($name)) {
                throw new \RuntimeException(sprintf(
                    'Global name should be a string, %s given',
                    gettype($name)
                ));
            }

            if (!$container->has($global)) {
                throw new \RuntimeException(sprintf('Container couldn\'t resolve global value "%s"', $global));
            }

            $providers[$name] = $container->get($global);
        }

        return new TwigProvidedGlobalsMiddleware($environment, $providers);
    }
}
