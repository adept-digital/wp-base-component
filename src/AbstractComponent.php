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
    public function __invoke(): void
    {
        $this->boot();
        add_action($this->getNamespace('init'), [$this, 'init']);
        add_action('init', [$this, 'doInit']);
    }

    /**
     * Called on namespaced 'boot' action.
     *
     * @return void
     */
    abstract public function boot(): void;

    /**
     * Called on namespaced 'init' action.
     *
     * @return void
     */
    abstract public function init(): void;

    /**
     * Triggers namespaced init action.
     *
     * Passes `$this` as the first parameter to allow hooking into the
     * component after it has booted.
     *
     * @return void
     */
    public function doInit(): void
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
        $path = $this->normalisePath($path);
        if (!file_exists("{$this->getBasePath()}{$path}")) {
            throw new NotFoundException($path);
        }

        return "{$this->getBasePath()}{$path}";
    }

    /**
     * @inheritDoc
     */
    public function getUri(string $path): string
    {
        $path = $this->normalisePath($path);
        $pathOnly = parse_url($path, PHP_URL_PATH);
        if ($pathOnly === false || $pathOnly === null) {
            throw new NotFoundException($path);
        }

        if (!file_exists("{$this->getBasePath()}{$pathOnly}")) {
            throw new NotFoundException($path);
        }

        return "{$this->getBaseUri()}{$path}";
    }

    /**
     * Ensure path uses forward slashes and starts with a slash.
     *
     * @param string $path
     * @return string
     */
    private function normalisePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $path = "/{$path}";
        $path = preg_replace('#/+#', '/', $path);

        return $path;
    }
}