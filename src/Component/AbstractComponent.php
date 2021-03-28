<?php

namespace AdeptDigital\WpBaseComponent;

use RuntimeException;

/**
 * Base class for themes and plugins
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * Component namespace.
     *
     * @var string
     */
    private string $namespace;

    /**
     * Path to file containing the component meta data.
     *
     * @var string
     */
    private string $file;

    /**
     * Component constructor.
     *
     * @param string $namespace
     * @param string $file
     */
    public function __construct(string $namespace, string $file)
    {
        $this->namespace = $namespace;
        $this->file = $file;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        do_action($this->getNamespace('boot'), $this);
        add_action('init', [$this, 'init']);
    }

    /**
     * Called on 'init' action and triggers namespaced init action.
     *
     * `$this` is passed to action to allow hooking into this component.
     *
     * @return void
     */
    public function init(): void
    {
        do_action($this->getNamespace('init'), $this);
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(string $name = null): string
    {
        if ($name === null || $name === '') {
            return $this->namespace;
        }

        return "{$this->namespace}_{$name}";
    }

    /**
     * @inheritDoc
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @inheritDoc
     */
    public function getPath(string $path): string
    {
        if (!file_exists("{$this->getBasePath()}/{$path}")) {
            throw new RuntimeException("Path not found: {$path}");
        }

        return "{$this->getBasePath()}/{$path}";
    }

    /**
     * @inheritDoc
     */
    public function getUri(string $path): string
    {
        if (!file_exists("{$this->getBasePath()}/{$path}")) {
            throw new RuntimeException("Path not found: {$path}");
        }

        return "{$this->getBaseUri()}/{$path}";
    }
}