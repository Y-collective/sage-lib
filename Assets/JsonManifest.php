<?php

declare(strict_types=1);

namespace Roots\Sage\Assets;

/**
 * Reads a JSON-encoded asset manifest (e.g. mix-manifest.json).
 *
 * @implements ManifestInterface
 */
class JsonManifest implements ManifestInterface
{
    /** @var array<string, string> Decoded manifest entries. */
    public array $manifest;

    /** @var string Remote URI prefix for assets. */
    public string $dist;

    /**
     * @param string $manifestPath Local filesystem path to the JSON manifest file.
     * @param string $distUri      Remote URI to the assets root directory.
     */
    public function __construct(string $manifestPath, string $distUri)
    {
        $this->manifest = is_file($manifestPath)
            ? (json_decode(file_get_contents($manifestPath), true) ?? [])
            : [];
        $this->dist = $distUri;
    }

    /**
     * @param  string $asset Original filename.
     * @return string Cache-busted filename, or the original if not found.
     */
    public function get($asset): string
    {
        return isset($this->manifest[$asset]) ? $this->manifest[$asset] : $asset;
    }

    /**
     * @param  string $asset Original filename.
     * @return string Full cache-busted URI.
     */
    public function getUri($asset): string
    {
        return "{$this->dist}/{$this->get($asset)}";
    }
}
