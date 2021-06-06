<?php

namespace BlancoHugo\Blog\Twig\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

/**
 * Middleware to process globals provided by callable
 *
 * @package BlancoHugo\Blog\Twig\Middleware
 */
class TwigProvidedGlobalsMiddleware implements MiddlewareInterface
{
    /**
     * Twig environment
     *
     * @var Environment
     */
    private $enviroment;

    /**
     * Global providers
     *
     * @var callable[]
     */
    private $globals;

    /**
     * Class constructor
     *
     * @param Environment $enviroment
     * @param callable[] $globals
     */
    public function __construct(Environment $enviroment, array $globals)
    {
        $this->enviroment = $enviroment;
        $this->globals = $globals ?: [];
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        foreach ($this->globals as $name => $provider) {
            if (!is_string($name) || !is_callable($provider)) {
                continue;
            }

            $this->enviroment->addGlobal($name, $provider());
        }

        return $handler->handle($request);
    }
}
