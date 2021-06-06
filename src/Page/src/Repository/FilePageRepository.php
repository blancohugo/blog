<?php

namespace BlancoHugo\Blog\Page\Repository;

use BlancoHugo\Blog\Common\Repository\FileRepository;
use BlancoHugo\Blog\Page\Model\Page;
use BlancoHugo\Blog\Page\Service\CreatePageFromFileService;

/**
 * Page data repository with files
 *
 * @package BlancoHugo\Blog\Page\Repository
 */
class FilePageRepository extends FileRepository implements PageRepositoryInterface
{
    /**
     * Service to create pages from files
     *
     * @var CreatePageFromFileService
     */
    private $createService;

    /**
     * Class constructor
     *
     * @param CreatePageFromFileService $createService
     * @param string $path
     * @param string $extension
     */
    public function __construct(CreatePageFromFileService $createService, string $path, string $extension)
    {
        parent::__construct($path, $extension);
        $this->createService = $createService;
    }

    /**
     * {@inheritDoc}
     */
    public function load(string $filename)
    {
        if (!$this->exists($filename)) {
            return null;
        }

        if ($this->isLoaded($filename)) {
            return $this->loadedFiles[$filename];
        }

        $page = ($this->createService)($this->makePath($filename));
        $this->loadedFiles[$filename] = $page;

        return $page;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(int $quantity = null): array
    {
        return $this->loadAll($quantity);
    }

    /**
     * {@inheritDoc}
     */
    public function findBySlug(string $slug): ?Page
    {
        return $this->load($slug);
    }

    /**
     * {@inheritDoc}
     */
    public function findPublished(int $quantity = null): array
    {
        if ($quantity) {
            return $this->filter(fn($page) => $page->isPublished(), $quantity);
        }

        return $this->filter(fn($page) => $page->isPublished());
    }
}
