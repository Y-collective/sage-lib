<?php

declare(strict_types=1);

namespace Roots\Sage\Template;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\ViewServiceProvider;

/**
 * Registers Blade-related services into an Illuminate container.
 *
 * Extends ViewServiceProvider to bind the filesystem, event dispatcher, config,
 * view finder (with namespace support), Blade compiler, engine resolver, and
 * view factory. Also registers a terminating callback for Component cache
 * flushing via the parent.
 */
class BladeProvider extends ViewServiceProvider
{
    /**
     * @param ContainerContract|null $container The container to bind into, or null to use the global instance.
     * @param array                  $config    Configuration array with 'view.paths', 'view.namespaces',
     *                                          and 'view.compiled' keys.
     */
    public function __construct(?ContainerContract $container = null, array $config = [])
    {
        parent::__construct($container ?: Container::getInstance());

        $this->app->bindIf('config', function () use ($config) {
            return $config;
        }, true);
    }

    /**
     * Register the service provider.
     *
     * Binds filesystem and event dispatcher first, then delegates to the parent
     * to register the view finder, Blade compiler, engine resolver, view factory,
     * and the Component::flushCache terminating callback.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFilesystem();
        $this->registerEvents();
        parent::register();
    }

    /**
     * Register the filesystem binding.
     *
     * @return void
     */
    public function registerFilesystem()
    {
        $this->app->bindIf('files', Filesystem::class, true);
    }

    /**
     * Register the event dispatcher binding.
     *
     * @return void
     */
    public function registerEvents()
    {
        $this->app->bindIf('events', Dispatcher::class, true);
    }

    /**
     * Register the view finder with namespace support.
     *
     * Overrides the parent to use FileViewFinder with hyphen-delimited fallback
     * resolution and configured view namespaces.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $this->app->bindIf('view.finder', function ($app) {
            $config = $this->app->make('config');
            $paths = $config->get('view.paths');
            $namespaces = $config->get('view.namespaces');
            $finder = new FileViewFinder($app->make('files'), $paths);
            array_map([$finder, 'addNamespace'], array_keys($namespaces), $namespaces);

            return $finder;
        }, true);
    }
}
