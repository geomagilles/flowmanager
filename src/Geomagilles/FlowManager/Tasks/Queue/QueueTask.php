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

use Decider;
use Worker;
use Queue;

use Geomagilles\FlowManager\Support\Payload\Payload;
use Geomagilles\FlowManager\Tasks\Task;

class QueueTask extends Task
{
    protected $workerTube = "default";
    protected $deciderTube = "default";

    public function forWorker($payload)
    {
        $job = '\\Geomagilles\\FlowManager\\Tasks\\Queue\\QueueTask@fireWorker';
        $data = $this->toJson($payload);

        Queue::setDefaultDriver('worker');
        Queue::push($job, $data);
        Queue::setDefaultDriver('beanstalkd');
    }

    public function fireWorker($job, $data)
    {
        $payload = Payload::create(json_decode($data, true));
        Worker::fire($payload);
        $job->delete();
    }

    public function forDecider($payload, $date = null)
    {
        $job = '\\Geomagilles\\FlowManager\\Tasks\\Queue\\QueueTask@fireDecider';
        $data = $this->toJson($payload);
        
        Queue::setDefaultDriver('decider');
        is_null($date) ? Queue::push($job, $data) : Queue::later($date, $job, $data);
        Queue::setDefaultDriver('beanstalkd');
    }

    public function fireDecider($job, $data)
    {
        $payload = Payload::create(json_decode($data, true));
        Decider::fire($payload);
        $job->delete();
    }
}
