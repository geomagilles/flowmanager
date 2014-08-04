<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Tasks;

interface TaskInterface
{
    /**
     * Fire a new task for worker.
     *
     * @param  mixed   $payload
     * @return void
     */
    public function forWorker($payload);

    /**
     * Fire a new task for Decider.
     *
     * @param  mixed   $payload
     * @return void
     */
    public function forDecider($payload, $date = null);
}
