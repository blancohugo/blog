<?php

namespace BlancoHugo\Blog\File;

/**
 * Factory to create resource files
 *
 * @package BlancoHugo\Blog\File
 */
class ResourceFileFactory
{
    /**
     * Checks if the resource file can be created
     *
     * @param string $path
     * @return boolean
     */
    public function canCreate(string $path): bool
    {
        return is_readable($path);
    }

    /**
     * Creates a new instance if the path is valid
     *
     * @param string $path
     * @return ResourceFile|null
     */
    public function __invoke(string $path): ?ResourceFile
    {
        if (!$this->canCreate($path)) {
            return null;
        }

        return new ResourceFile($path);
    }
}
