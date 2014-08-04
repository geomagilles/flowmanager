<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Tasks\Queue;

use Geomagilles\FlowManager\Tasks\ConnectorInterface;

class QueueConnector implements ConnectorInterface
{
    /**
     * Establish a task connection by queue.
     *
     * @param  array  $config
     * @return FlowManager\Tasks\Queue\QueueTask
     */
    public function connect(array $config)
    {
        $task = new QueueTask();
        $task->setDeciderQueue($config['decider']['queue']);
        $task->setWorkerQueue($config['worker']['queue']);
        return $task;
    }
}
