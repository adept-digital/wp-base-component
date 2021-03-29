<?php

namespace AdeptDigital\WpBaseComponent\Exception;

use RuntimeException;

/**
 * Not Found Exception
 */
class NotFoundException extends RuntimeException
{
    /**
     * Missing path.
     *
     * @var string
     */
    private string $path;

    /**
     * Not Found Exception constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("Path not found: {$path}");
        $this->path = $path;
    }

    /**
     * Get the missing path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}