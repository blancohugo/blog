<?php

namespace BlancoHugo\Blog\Page\Service;

use BlancoHugo\Blog\File\ResourceFileFactory;
use BlancoHugo\Blog\Page\Model\Page;

/**
 * Service to create pages from resource files
 *
 * @package BlancoHugo\Blog\Page\Service
 */
class CreatePageFromFileService
{
    /**
     * Resource file factory
     *
     * @var ResourceFileFactory
     */
    private $fileFactory;

    /**
     * Class constructor
     *
     * @param ResourceFileFactory $fileFactory
     */
    public function __construct(ResourceFileFactory $fileFactory)
    {
        $this->fileFactory = $fileFactory;
    }

    /**
     * Executes the service
     *
     * @param string $path
     * @return Page|null
     */
    public function __invoke(string $path): ?Page
    {
        if (!$file = ($this->fileFactory)($path)) {
            return null;
        }

        $slug = $file->getFilename();
        $content = $file->getContent();
        $title = $file->getMetadata('title', 'Untitled');
        $published = $file->getMetadata('published', 'true');

        $page = new Page($title, $slug, $content);
        $page->setPublished($published === 'true' ? true : false);

        return $page;
    }
}
