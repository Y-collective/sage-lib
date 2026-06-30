<?php

declare(strict_types=1);

namespace Roots\Sage\Template;

/**
 * Extends Illuminate's FileViewFinder with hyphen-delimited template fallback.
 *
 * Given a view name like "partials-sidebar", this finder generates candidate
 * templates by progressively removing hyphen-separated segments, allowing
 * fallback from specific to generic partials.
 *
 * @extends \Illuminate\View\FileViewFinder
 */
class FileViewFinder extends \Illuminate\View\FileViewFinder
{
    /** @var string Delimiter used for fallback parts in template names. */
    public const FALLBACK_PARTS_DELIMITER = '-';

    /**
     * Get an array of possible view files from a single file name.
     *
     * Builds a fallback chain by progressively removing the last hyphen-delimited
     * segment, then returns all matching files for each candidate.
     *
     * @param  string $name The view name (e.g. "partials-sidebar").
     * @return array List of possible view file paths.
     */
    public function getPossibleViewFiles($name)
    {
        $parts = explode(self::FALLBACK_PARTS_DELIMITER, $name);
        $templates[] = array_shift($parts);
        foreach ($parts as $i => $part) {
            $templates[] = $templates[$i] . self::FALLBACK_PARTS_DELIMITER . $part;
        }
        rsort($templates);
        return $this->getPossibleViewFilesFromTemplates($templates);
    }

    /**
     * Map an array of template names to all possible view file paths.
     *
     * Each template name is combined with every registered extension.
     *
     * @param  array $templates List of template name candidates.
     * @return array Flat list of possible view file paths.
     */
    public function getPossibleViewFilesFromTemplates($templates)
    {
        $mapped = array_map(function ($template) {
            return array_map(function ($extension) use ($template) {
                return str_replace('.', '/', $template) . '.' . $extension;
            }, $this->extensions);
        }, $templates);

        return $mapped ? array_merge(...$mapped) : [];
    }
}
