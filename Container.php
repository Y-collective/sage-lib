<?php

namespace Roots\Sage;

use Illuminate\Container\Container as BaseContainer;

class Container extends BaseContainer
{
    /**
     * The array of terminating callbacks.
     *
     * @var callable[]
     */
    protected $terminatingCallbacks = [];

    /**
     * Register a terminating callback with the application.
     *
     * @param callable|string $callback
     * @return $this
     */
    public function terminating($callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    /**
     * Terminate the application.
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
