<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Support\Driver;

use Closure;

abstract class DriverManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The array of resolved task connections.
     *
     * @var array
     */
    protected $connections = array();

    /**
     * Create a new task manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Resolve a task instance.
     *
     * @param  string  $name
     * @return FlowManager\Tasks\TaskInterface
     */
    public function create($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        // If the connection has not been resolved yet we will resolve it now as all
        // of the connections are resolved when they are actually needed so we do
        // not make any unnecessary connection to the various task end-points.
        if (! isset($this->connections[$name])) {
            $this->connections[$name] = $this->resolve($name);
            $this->connections[$name]->setContainer($this->app);
        }

        return $this->connections[$name];
    }

    /**
     * Resolve a task connection.
     *
     * @param  string  $name
     * @return FlowManager\Tasks\TaskInterface
     */
    protected function resolve($name)
    {

        $config = $this->getConfig($name);

        return $this->getConnector($config['driver'])->connect($config);
    }

    /**
     * Get the connector for a given driver.
     *
     * @param  string  $driver
     * @return FlowGraph\Workers\ConnectorInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function getConnector($driver)
    {
        if (isset($this->connectors[$driver])) {
            return call_user_func($this->connectors[$driver]);
        }

        throw new \InvalidArgumentException("No connector for [$driver]");
    }

    /**
     * Add a task connection resolver.
     *
     * @param  string   $driver
     * @param  Closure  $resolver
     * @return void
     */
    public function addConnector($driver, Closure $resolver)
    {
        $this->connectors[$driver] = $resolver;
    }

    /**
     * Dynamically pass calls to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $callable = array($this->create(), $method);

        return call_user_func_array($callable, $parameters);
    }

    /**
     * Get the task connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    abstract protected function getConfig($name);

    /**
     * Get the name of the default task connection.
     *
     * @return string
     */
    abstract protected function getDefaultDriver();
}
