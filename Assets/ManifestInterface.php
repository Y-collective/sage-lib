<?php

declare(strict_types=1);

namespace Roots\Sage\Assets;

/**
 * Interface for cache-busting asset manifest readers.
 */
interface ManifestInterface
{
    /**
     * Get the cache-busted filename for the given asset.
     *
     * Returns the original asset name if no entry exists in the manifest.
     *
     * @param  string $asset Original filename before cache-busting.
     * @return string Cache-busted filename.
     */
    public function get($asset): string;

    /**
     * Get the full cache-busted URI for the given asset.
     *
     * Prepends the dist URI to the result of get().
     *
     * @param  string $asset Original filename before cache-busting.
     * @return string Full URI to the cache-busted asset.
     */
    public function getUri($asset): string;
}
