<?php

declare(strict_types=1);

namespace BlancoHugo\Blog\Handler;

use BlancoHugo\Blog\Post\Repository\PostRepositoryInterface;
use Laminas\Diactoros\Response;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware to handle home page requests
 *
 * @package BlancoHugo\Blog\Handler
 */
class HomePageHandler implements RequestHandlerInterface
{
    /**
     * Template renderer
     *
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * Post repository
     *
     * @var PostRepositoryInterface
     */
    private $repository;

    /**
     * Class constructor
     *
     * @param TemplateRendererInterface $template
     * @param PostRepositoryInterface $repository
     */
    public function __construct(TemplateRendererInterface $template, PostRepositoryInterface $repository)
    {
        $this->template = $template;
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $posts = $this->repository->findPublished(6);
        return new Response\HtmlResponse($this->template->render('app::home-page', ['posts' => $posts]));
    }
}
