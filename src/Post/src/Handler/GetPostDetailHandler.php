<?php

namespace BlancoHugo\Blog\Post\Handler;

use BlancoHugo\Blog\Post\Repository\PostRepositoryInterface;
use Laminas\Diactoros\Response;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware to handle post detail requests
 *
 * @package BlancoHugo\Blog\Post\Handler
 */
class GetPostDetailHandler implements RequestHandlerInterface
{
    /**
     * Template renderer
     *
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * Post data repository
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
        $postSlug = $request->getAttribute('post');

        if (!$postSlug || !is_string($postSlug)) {
            return new Response\HtmlResponse($this->template->render('error::404'));
        }

        $post = $this->repository->findBySlug($postSlug);

        if (!$post) {
            return new Response\HtmlResponse($this->template->render('error::404'));
        }

        return new Response\HtmlResponse($this->template->render('post::detail', ['post' => $post]));
    }
}
