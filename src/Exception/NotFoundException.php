<?php

namespace AdeptDigital\WpBaseComponent\Exception;

use RuntimeException;

/**
 * Not Found Exception
 */
class NotFoundException extends RuntimeException
{
    /**
     * Not Found Exception constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct("Not found: {$path}");
    }
}