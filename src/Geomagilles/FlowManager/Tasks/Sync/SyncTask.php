<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Tasks\Sync;

use Symfony\Component\Console\Output\ConsoleOutput;

use Geomagilles\FlowManager\Decider\DeciderFacade as Decider;
use Geomagilles\FlowManager\Worker\WorkerFacade as Worker;
use Geomagilles\FlowManager\Support\Payload\Payload;
use Geomagilles\FlowManager\Tasks\Task;

class SyncTask extends Task
{
    public function forWorker($payload, $date = null)
    {
        if (is_array($payload)) {
            $payload = Payload::create($payload);
        }

        Worker::fire($payload);
    }

    public function startWorker(ConsoleOutput $output)
    {

    }

    public function forDecider($payload, $date = null)
    {
        if (is_array($payload)) {
            $payload = Payload::create($payload);
        }

        Decider::fire($payload);
    }

    public function startDecider(ConsoleOutput $output)
    {

    }
}
