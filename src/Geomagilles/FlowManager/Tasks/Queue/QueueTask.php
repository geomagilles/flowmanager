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

use Queue;
use Config;

use Geomagilles\FlowManager\Decider\DeciderFacade as Decider;
use Geomagilles\FlowManager\Worker\WorkerFacade as Worker;
use Geomagilles\FlowManager\Support\Payload\Payload;
use Geomagilles\FlowManager\Tasks\Task;

class QueueTask extends Task
{
    protected $workerQueue = "default";
    protected $deciderQueue = "default";

    public function forWorker($payload)
    {
        $job = __CLASS__.'@fireWorker';
        $data = $this->toJson($payload);

        Queue::push($job, $data, $this->workerQueue);
    }

    public function fireWorker($job, $data)
    {
        $payload = Payload::create(json_decode($data, true));
        Worker::fire($payload);
        $job->delete();
    }

    public function setWorkerQueue($queue)
    {
        $this->workerQueue = $queue;
    }

    public function forDecider($payload, $date = null)
    {
        $job = __CLASS__.'@fireDecider';
        $data = $this->toJson($payload);
        
        is_null($date) ?
            Queue::push($job, $data, $this->deciderQueue) :
            Queue::later($date, $job, $data, $this->deciderQueue);
    }

    public function fireDecider($job, $data)
    {
        $payload = Payload::create(json_decode($data, true));
        Decider::fire($payload);
        $job->delete();
    }

    public function setDeciderQueue($queue)
    {
        $this->deciderQueue = $queue;
    }
}
