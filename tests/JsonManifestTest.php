<?php

declare(strict_types=1);

namespace Roots\Sage\Tests;

use PHPUnit\Framework\TestCase;
use Roots\Sage\Assets\JsonManifest;

/**
 * @covers \Roots\Sage\Assets\JsonManifest
 */
class JsonManifestTest extends TestCase
{
    public function testReturnsAssetAsIsWhenNotInManifest(): void
    {
        $manifest = new JsonManifest(__FILE__, '/dist');
        $this->assertSame('foo.js', $manifest->get('foo.js'));
    }

    public function testReturnsCachedFilenameFromManifest(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'sage_');
        file_put_contents($path, json_encode(['app.js' => 'app.abc123.js']));

        $manifest = new JsonManifest($path, '/dist');
        $this->assertSame('app.abc123.js', $manifest->get('app.js'));

        unlink($path);
    }

    public function testReturnsUriWithDistPrefix(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'sage_');
        file_put_contents($path, json_encode(['app.js' => 'app.abc123.js']));

        $manifest = new JsonManifest($path, '/dist');
        $this->assertSame('/dist/app.abc123.js', $manifest->getUri('app.js'));

        unlink($path);
    }

    public function testHandlesMissingManifestFileGracefully(): void
    {
        $manifest = new JsonManifest('/nonexistent/manifest.json', '/dist');
        $this->assertSame('app.js', $manifest->get('app.js'));
    }

    public function testHandlesInvalidJsonGracefully(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'sage_');
        file_put_contents($path, 'not json');

        $manifest = new JsonManifest($path, '/dist');
        $this->assertSame('app.js', $manifest->get('app.js'));

        unlink($path);
    }
}
