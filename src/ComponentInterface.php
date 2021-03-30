<?php

namespace AdeptDigital\WpBaseComponent;

/**
 * Component Interface
 */
interface ComponentInterface
{
    /**
     * Starts the component.
     *
     * @return void
     */
    public function __invoke(): void;

    /**
     * Get WordPress' id for the component
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Get the unique namespace for the component.
     *
     * Used as a prefix for actions/filters, options, transients, assets, etc.
     *
     * @return string
     */
    public function getBaseNamespace(): string;

    /**
     * Get a unique namespace for the component.
     *
     * Used as a prefix for actions/filters, options, transients, assets, etc.
     *
     * @param string $name
     * @return string
     */
    public function getNamespace(string $name): string;

    /**
     * Get path to file containing the component meta data.
     *
     * @return string
     */
    public function getFile(): string;

    /**
     * Get base filesystem path for the component.
     *
     * @return string
     */
    public function getBasePath(): string;

    /**
     * Get a filesystem path for the component.
     *
     * @param string $path
     * @return string
     * @throws Exception\NotFoundException
     */
    public function getPath(string $path): string;

    /**
     * Get base URI for the component.
     *
     * @return string
     */
    public function getBaseUri(): string;

    /**
     * Get a URI for the component.
     *
     * @param string $path
     * @return string
     * @throws Exception\NotFoundException
     */
    public function getUri(string $path): string;

    /**
     * Get meta data for the component.
     *
     * @param string $name
     * @return string
     */
    public function getMetaData(string $name): ?string;
}