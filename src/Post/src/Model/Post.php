<?php

namespace BlancoHugo\Blog\Post\Model;

/**
 * Post data model
 *
 * @package BlancoHugo\Blog\Post\Model
 */
class Post
{
    /**
     * Post title
     *
     * @var string
     */
    protected $title;

    /**
     * Post summary
     *
     * @var string
     */
    protected $summary;

    /**
     * Post slug (friendly url)
     *
     * @var string
     */
    protected $slug;

    /**
     * Post body
     *
     * @var string
     */
    protected $body;

    /**
     * Post author
     *
     * @var string|null
     */
    protected $author;

    /**
     * Creation date
     *
     * @var \DateTimeInterface
     */
    protected $date;

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
     * @param string $summary
     * @param string $slug
     * @param string $body
     */
    public function __construct(string $title, string $summary, string $slug, string $body)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->summary = $summary;
        $this->body = $body;

        $this->date = new \DateTimeImmutable('now');
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
     * Returns the page summary
     *
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Returns the page slug (friendly url)
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
     * Sets the post author
     *
     * @param string $author
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Returns the post author
     *
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * Sets the post creation date
     *
     * @param \DateTimeInterface $date
     * @return self
     */
    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Returns the post creation date
     *
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
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
     * Checks if the post is published
     *
     * @return boolean
     */
    public function isPublished(): bool
    {
        return $this->published === true;
    }
}
