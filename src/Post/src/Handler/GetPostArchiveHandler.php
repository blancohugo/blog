<?php

namespace BlancoHugo\Blog\Post\Handler;

use BlancoHugo\Blog\Post\Repository\PostRepositoryInterface;
use BlancoHugo\Blog\Post\Service\GroupPostsByDateService;
use Laminas\Diactoros\Response;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware to handle post archive page
 *
 * @package BlancoHugo\Blog\Post\Handler
 */
class GetPostArchiveHandler implements RequestHandlerInterface
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
     * Post grouper
     *
     * @var GroupPostsByDateService
     */
    private $grouper;

    /**
     * Class constructor
     *
     * @param TemplateRendererInterface $template
     * @param PostRepositoryInterface $repository
     * @param GroupPostsByDateService $grouper
     */
    public function __construct(
        TemplateRendererInterface $template,
        PostRepositoryInterface $repository,
        GroupPostsByDateService $grouper
    ) {
        $this->template = $template;
        $this->repository = $repository;
        $this->grouper = $grouper;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $posts = $this->repository->findPublished();
        $posts = ($this->grouper)($posts);

        return new Response\HtmlResponse($this->template->render('post::archive', ['posts' => $posts]));
    }
}
