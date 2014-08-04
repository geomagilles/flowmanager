<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Tasks\Swf;

use Geomagilles\FlowManager\Tasks\Task;
use Aws\Swf\SwfClient;
use Illuminate\Container\Container;

class SwfTask extends Task
{

    /**
     * The Amazon SQS client instance.
     *
     * @var \Aws\Swf\SwfClient
     */
    protected $swf;

    /**
     * The Amazon SQS job instance.
     *
     * @var array
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Aws\Swf\SwfClient  $swf
     * @param  string  $queue
     * @param  array   $job
     * @return void
     */
    public function __construct(
        Container $container,
        SwfClient $swf,
        $queue,
        array $job
    ) {
        $this->swf = $swf;
        $this->job = $job;
        $this->queue = $queue;
        $this->container = $container;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        $this->resolveAndFire(json_decode($this->getRawBody(), true));
    }

    /**
     * Is this job synchronous?
     *
     * @return boolean
     */
    public function isSync()
    {
        return false;
    }

    /**
     * Get the raw body string for the job.
     *
     * @return string
     */
    public function getRawBody()
    {
        return $this->job['Body'];
    }

    /**
     * Get the underlying SQS client instance.
     *
     * @return \Aws\Swf\SwfClient
     */
    public function getSwf()
    {
        return $this->swf;
    }

    /**
     * Get the underlying raw SQS job.
     *
     * @return array
     */
    public function getSwfTask()
    {
        return $this->job;
    }
}
