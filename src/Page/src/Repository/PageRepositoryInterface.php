<?php

namespace BlancoHugo\Blog\Page\Repository;

use BlancoHugo\Blog\Page\Model\Page;

/**
 * Page data repository interface
 *
 * @package BlancoHugo\Blog\Page\Repository
 */
interface PageRepositoryInterface
{
    /**
     * Find pages by slug
     *
     * @param string $slug
     * @return Page|null
     */
    public function findBySlug(string $slug): ?Page;

    /**
     * Find all pages
     *
     * @param integer $quantity
     * @return array
     */
    public function findAll(int $quantity = null): array;

    /**
     * Find published pages
     *
     * @param integer $quantity
     * @return array
     */
    public function findPublished(int $quantity = null): array;
}
