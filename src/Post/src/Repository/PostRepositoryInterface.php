<?php

namespace BlancoHugo\Blog\Post\Repository;

use BlancoHugo\Blog\Post\Model\Post;

/**
 * Post data repository interface
 *
 * @package BlancoHugo\Blog\Post\Repository
 */
interface PostRepositoryInterface
{
    /**
     * Find a post by slug
     *
     * @param string $slug
     * @return Post|null
     */
    public function findBySlug(string $slug): ?Post;

    /**
     * Find all posts
     *
     * @param integer $quantity
     * @return array
     */
    public function findAll(int $quantity = null): array;
}
