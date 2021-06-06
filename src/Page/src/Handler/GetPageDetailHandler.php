<?php

namespace BlancoHugo\Blog\Page\Handler;

use BlancoHugo\Blog\Page\Repository\PageRepositoryInterface;
use Laminas\Diactoros\Response;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware to handle page detail requests
 *
 * @package BlancoHugo\Blog\Page\Handler
 */
class GetPageDetailHandler implements RequestHandlerInterface
{
    /**
     * Template renderer
     *
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * Page data repository
     *
     * @var PageRepositoryInterface
     */
    private $repository;

    /**
     * Class constructor
     *
     * @param TemplateRendererInterface $template
     * @param PageRepositoryInterface $repository
     */
    public function __construct(TemplateRendererInterface $template, PageRepositoryInterface $repository)
    {
        $this->template = $template;
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pageSlug = $request->getAttribute('page');

        if (!$pageSlug || !is_string($pageSlug)) {
            return new Response\HtmlResponse($this->template->render('error::404'));
        }

        $page = $this->repository->findBySlug($pageSlug);

        if (!$page) {
            return new Response\HtmlResponse($this->template->render('error::404'));
        }

        return new Response\HtmlResponse($this->template->render('page::detail', ['page' => $page]));
    }
}
