<?php

namespace BlancoHugo\Blog\Post\Service;

use BlancoHugo\Blog\Post\Model\Post;

/**
 * Service to group posts by date
 *
 * @package BlancoHugo\Blog\Post\Service
 */
class GroupPostsByDateService
{
    /**
     * Executes the service
     *
     * @param array $posts
     * @return array
     */
    public function __invoke(array $posts): array
    {
        $grouped = [];

        foreach ($posts as $post) {
            if (!$post instanceof Post) {
                continue;
            }

            $date = $post->getDate()->format('Y-m');

            if (!array_key_exists($date, $grouped)) {
                $grouped[$date] = [];
            }

            $grouped[$date][] = $post;
        }

        krsort($grouped);
        return $grouped;
    }
}
