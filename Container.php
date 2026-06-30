<?php

declare(strict_types=1);

namespace Roots\Sage;

use Illuminate\Container\Container as BaseContainer;

/**
 * @extends BaseContainer
 */
class Container extends BaseContainer
{
    /**
     * The array of terminating callbacks.
     *
     * @var callable[]
     */
    protected $terminatingCallbacks = [];

/**
 * Register a terminating callback with the container.
 *
 * @param  callable|string $callback
 * @return $this
 */
    public function terminating($callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

/**
 * Execute all registered terminating callbacks.
 *
 * @return void
 */
    public function terminate()
    {
        $index = 0;

        while ($index < count($this->terminatingCallbacks)) {
            $this->call($this->terminatingCallbacks[$index]);

            $index++;
        }
    }
}
