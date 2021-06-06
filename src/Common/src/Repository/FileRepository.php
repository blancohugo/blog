<?php

namespace BlancoHugo\Blog\Common\Repository;

use Webmozart\Glob\Glob;

/**
 * Abstract file data repository
 *
 * @package BlancoHugo\Blog\Common\Repository
 */
abstract class FileRepository
{
    /**
     * Directory path
     *
     * @var string
     */
    protected $directory;

    /**
     * Extension to load files
     *
     * @var string
     */
    protected $extension;

    /**
     * Loaded files
     *
     * @var array
     */
    protected $loadedFiles;

    /**
     * Class constructor
     *
     * @param string $directory
     * @param string $extension
     * @throws \InvalidArgumentException If the directory provided is not valid
     */
    public function __construct(string $directory, string $extension)
    {
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not a valid directory', $directory));
        }

        $this->directory = $directory;
        $this->extension = $extension;
        $this->loadedFiles = [];
    }

    /**
     * Loads and parses a file if it's not already loaded
     *
     * @param string $filename
     * @return mixed
     */
    public function load(string $filename)
    {
        if (!$this->exists($filename)) {
            return null;
        }

        if ($this->isLoaded($filename)) {
            return $this->loadedFiles[$filename];
        }

        $file = file_get_contents($this->makePath($filename));
        $this->loadedFiles[$filename] = $file;

        return $file;
    }

    /**
     * Loads all files and returns the list
     *
     * @param integer $quantity
     * @return array
     */
    public function loadAll(int $quantity = null): array
    {
        $files = Glob::glob("{$this->directory}/*.{$this->extension}");
        $files = array_reverse($files);

        if ($quantity) {
            $files = array_slice($files, 0, $quantity);
        }

        $loadedFiles = [];

        foreach ($files as $file) {
            $filename = basename($file, ".{$this->extension}");
            $this->load($filename);
        }

        return $this->loadedFiles;
    }

    /**
     * Filters all files by a given callable
     *
     * @param callable $filter
     * @param integer $quantity
     * @return array
     */
    public function filter(callable $filter, int $quantity = null): array
    {
        $files = array_filter($this->loadAll(), $filter);

        if ($quantity) {
            return array_slice($files, 0, $quantity);
        }

        return $files;
    }

    /**
     * Returns the full file path
     *
     * @param string $filename
     * @return string
     */
    protected function makePath(string $filename): string
    {
        return "{$this->directory}/{$filename}.{$this->extension}";
    }

    /**
     * Checks if the file exists
     *
     * @param string $filename
     * @return boolean
     */
    protected function exists(string $filename): bool
    {
        return is_readable($this->makePath($filename));
    }

    /**
     * Checks if the file is already loaded
     *
     * @param string $filename
     * @return boolean
     */
    protected function isLoaded(string $filename): bool
    {
        return array_key_exists($filename, $this->loadedFiles);
    }
}
