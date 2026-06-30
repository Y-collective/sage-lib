<?php

declare(strict_types=1);

namespace Roots\Sage\Tests;

use PHPUnit\Framework\TestCase;
use Roots\Sage\Template\FileViewFinder;

/**
 * @covers \Roots\Sage\Template\FileViewFinder
 */
class FileViewFinderTest extends TestCase
{
    public function testFallbackPartsDelimiterIsDefined(): void
    {
        $this->assertSame('-', FileViewFinder::FALLBACK_PARTS_DELIMITER);
    }

    public function testGetPossibleViewFilesFromTemplatesReturnsEmptyArrayForNoTemplates(): void
    {
        $finder = $this->createFinderWithExtensions([]);

        $result = $this->invokeMethod($finder, 'getPossibleViewFilesFromTemplates', [[]]);
        $this->assertSame([], $result);
    }

    public function testGetPossibleViewFilesFromTemplatesReturnsExpectedFiles(): void
    {
        $finder = $this->createFinderWithExtensions(['blade.php', 'php']);

        $result = $this->invokeMethod($finder, 'getPossibleViewFilesFromTemplates', [['index']]);
        $this->assertContains('index.blade.php', $result);
        $this->assertContains('index.php', $result);
    }

    public function testGetPossibleViewFilesHandlesHyphenatedName(): void
    {
        $finder = $this->createFinderWithExtensions(['blade.php']);

        $result = $this->invokeMethod($finder, 'getPossibleViewFiles', ['partials-sidebar']);
        $this->assertContains('partials-sidebar.blade.php', $result);
    }

    /**
     * Create a FileViewFinder with the given extensions set via reflection.
     *
     * @param  array $extensions View file extensions to set.
     * @return FileViewFinder
     */
    private function createFinderWithExtensions(array $extensions): FileViewFinder
    {
        $filesMock = $this->createStub(\Illuminate\Filesystem\Filesystem::class);
        $finder = new FileViewFinder($filesMock, []);

        $reflection = new \ReflectionClass($finder);
        $prop = $reflection->getParentClass()->getProperty('extensions');
        $prop->setValue($finder, $extensions);

        return $finder;
    }

    /**
     * Invoke a protected/private method on an object via reflection.
     *
     * @param  object $object     The object to invoke the method on.
     * @param  string $methodName The method name to invoke.
     * @param  array  $args       Arguments to pass to the method.
     * @return mixed
     */
    private function invokeMethod($object, string $methodName, array $args = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($methodName);

        return $method->invokeArgs($object, $args);
    }
}
