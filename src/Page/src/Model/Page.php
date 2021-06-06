<?php

namespace BlancoHugo\Blog\Page\Model;

/**
 * Additional pages data model
 *
 * @package BlancoHugo\Blog\Page\Model
 */
class Page
{
    /**
     * Page title
     *
     * @var string
     */
    protected $title;

    /**
     * Page slug (friendly url)
     *
     * @var string
     */
    protected $slug;

    /**
     * Page content
     *
     * @var string
     */
    protected $body;

    /**
     * Published status
     *
     * @var boolean
     */
    protected $published;

    /**
     * Class constructor
     *
     * @param string $title
     * @param string $slug
     * @param string $body
     */
    public function __construct(string $title, string $slug, string $body)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->body = $body;
        $this->published = true;
    }

    /**
     * Returns the page title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns the page slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Returns the page body
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sets the published status
     *
     * @param boolean $published
     * @return self
     */
    public function setPublished(bool $published): self
    {
        $this->published = $published;
        return $this;
    }

    /**
     * Checks if the page is published
     *
     * @return boolean
     */
    public function isPublished(): bool
    {
        return $this->published === true;
    }
}
