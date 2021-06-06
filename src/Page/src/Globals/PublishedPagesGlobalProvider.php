<?php

namespace BlancoHugo\Blog\Page\Globals;

use BlancoHugo\Blog\Page\Repository\PageRepositoryInterface;
use BlancoHugo\Blog\Twig\Globals\GlobalProviderInterface;

/**
 * Global provider of published pages
 *
 * @package BlancoHugo\Blog\Page\Globals
 */
class PublishedPagesGlobalProvider implements GlobalProviderInterface
{
    /**
     * Page data repository
     *
     * @var PageRepositoryInterface
     */
    private $repository;

    /**
     * Class constructor
     *
     * @param PageRepositoryInterface $repository
     */
    public function __construct(PageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke()
    {
        return $this->repository->findPublished();
    }
}
