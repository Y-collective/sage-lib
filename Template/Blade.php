<?php

declare(strict_types=1);

namespace Roots\Sage\Template;

use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\View\ViewFinderInterface;

/**
 * High-level facade over the Illuminate View Factory for Blade templates.
 *
 * Proxies unknown method calls to the underlying Factory instance.
 *
 * @method bool                                   exists(string $view)
 * @method mixed                                  share(array|string $key, mixed $value = null)
 * @method array                                  creator(array|string $views, \Closure|string $callback)
 * @method array                                  composer(array|string $views, \Closure|string $callback)
 * @method \Illuminate\View\View                  file(string $file, array $data = [], array $mergeData = [])
 * @method \Illuminate\View\View                  make(string $file, array $data = [], array $mergeData = [])
 * @method \Illuminate\View\View                  addNamespace(string $namespace, string|array $hints)
 * @method \Illuminate\View\View                  replaceNamespace(string $namespace, string|array $hints)
 * @method \Illuminate\Contracts\Container\Container getContainer()
 */
class Blade
{
    /** @var FactoryContract The underlying view factory. */
    protected \Illuminate\Contracts\View\Factory $env;

    /** @var \Illuminate\View\Engines\EngineResolver|null Cached engine resolver. */
    protected ?\Illuminate\View\Engines\EngineResolver $engineResolver = null;

    /**
     * @param FactoryContract $env The view factory instance.
     */
    public function __construct(FactoryContract $env)
    {
        $this->env = $env;
    }

    /**
     * Get the Blade compiler instance.
     *
     * Lazily resolves and caches the engine resolver from the container.
     *
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    public function compiler()
    {
        if (!$this->engineResolver) {
            $this->engineResolver = $this->getContainer()->make('view.engine.resolver');
        }
        return $this->engineResolver->resolve('blade')->getCompiler();
    }

    /**
     * Render a view or file path as a string.
     *
     * Resolves file paths directly via file() and named views via make().
     *
     * @param  string $view      View name or file path.
     * @param  array  $data      Data to pass to the view.
     * @param  array  $mergeData Additional merge data.
     * @return string Rendered content.
     */
    public function render($view, $data = [], $mergeData = []): string
    {
        $filesystem = $this->getContainer()->make('files');
        $method = $filesystem->exists($view) ? 'file' : 'make';
        return $this->{$method}($view, $data, $mergeData)->render();
    }

    /**
     * Get the compiled (cached) file path for a given view.
     *
     * Compiles the view if the cached copy is expired.
     *
     * @param  string $file      View file path.
     * @param  array  $data      Data for rendering (unused in path resolution).
     * @param  array  $mergeData Merge data (unused in path resolution).
     * @return string Compiled file path.
     */
    public function compiledPath($file, $data = [], $mergeData = []): string
    {
        $rendered = $this->file($file, $data, $mergeData);
        $engine = $rendered->getEngine();

        if (!($engine instanceof CompilerEngine)) {
            return $file;
        }

        $compiler = $engine->getCompiler();
        $compiledPath = $compiler->getCompiledPath($rendered->getPath());
        if ($compiler->isExpired($compiledPath)) {
            $compiler->compile($file);
        }
        return $compiledPath;
    }

    /**
     * Normalise a file path to a dot-notated view name.
     *
     * Strips registered view paths, file extensions, and leading slashes.
     *
     * @param  string $file Absolute or relative file path.
     * @return string Normalised view name.
     */
    public function normalizeViewPath($file): string
    {
        $view = str_replace('\\', '/', $file);
        $view = $this->applyNamespaceToPath($view);
        $view = str_replace(array_merge(
            $this->getContainer()->make('config')->get('view.paths'),
            ['.blade.php', '.php', '.css']
        ), '', $view);
        return ltrim(preg_replace('%//+%', '/', $view), '/');
    }

    /**
     * Apply view namespace hints to a file path.
     *
     * Replaces registered hint paths with their namespace prefix.
     *
     * @param  string $path File path to process.
     * @return string Path with namespace prefix applied.
     */
    public function applyNamespaceToPath($path): string
    {
        $finder = $this->getContainer()->make('view.finder');
        if (!method_exists($finder, 'getHints')) {
            return $path;
        }
        $delimiter = $finder::HINT_PATH_DELIMITER;
        $hints = $finder->getHints();
        $view = array_reduce(array_keys($hints), function ($view, $namespace) use ($delimiter, $hints) {
            return str_replace($hints[$namespace], $namespace . $delimiter, $view);
        }, $path);
        return preg_replace("%{$delimiter}[\\\\/]*%", $delimiter, $view);
    }

    /**
     * Proxy method calls to the underlying view Factory.
     *
     * @param  string $method Method name.
     * @param  array  $params Parameters.
     * @return mixed
     */
    public function __call($method, $params)
    {
        return $this->env->{$method}(...$params);
    }
}
