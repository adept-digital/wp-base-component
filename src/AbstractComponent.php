<?php

namespace AdeptDigital\WpBaseComponent;

use AdeptDigital\WpBaseComponent\Exception\NotFoundException;

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
    public function getBaseNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(string $name): string
    {
        return "{$this->getBaseNamespace()}_{$name}";
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
            throw new NotFoundException($path);
        }

        return "{$this->getBasePath()}/{$path}";
    }

    /**
     * @inheritDoc
     */
    public function getUri(string $path): string
    {
        if (!file_exists("{$this->getBasePath()}/{$path}")) {
            throw new NotFoundException($path);
        }

        return "{$this->getBaseUri()}/{$path}";
    }
}