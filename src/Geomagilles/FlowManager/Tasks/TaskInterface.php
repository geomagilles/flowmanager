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

use Symfony\Component\Console\Output\ConsoleOutput;

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

    /**
     * Start a new worker.
     *
     * @return void
     */
    public function startWorker(ConsoleOutput $output);

    /**
     * Start a new decider.
     *
     * @return void
     */
    public function startDecider(ConsoleOutput $output);
}
