<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Engine;

use Geomagilles\FlowManager\Models\Instance\InstanceInterface;
use Geomagilles\FlowManager\Support\Payload\Payload;
use Geomagilles\FlowManager\Tasks\TaskManager;
use Geomagilles\FlowGraph\Events\BoxEvent;
use Geomagilles\FlowGraph\Events\GraphEvent;
use Geomagilles\FlowGraph\Events\TriggerEvent;
use Geomagilles\FlowGraph\Engine\Engine as FlowEngine;
use Geomagilles\FlowGraph\Engine\EngineInterface as FlowEngineInterface;

/**
 * Class to run an instance
 */
class Engine implements EngineInterface
{
    protected $engine;
    protected $instance;
    protected $jobEvents;

    public function __construct(
        InstanceInterface $instance,
        TaskManager $task,
        FlowEngineInterface $engine = null
    ) {
        $this->instance = $instance;
        $this->task = $task;
        $this->engine = is_null($engine) ? new FlowEngine() : $engine;

        // init engine
        $graph = $this->instance->getGraph();
        $dispatcher = $graph->getEventDispatcher();

        $this->engine->setGraph($graph);
        $this->engine->setData($this->instance->getData());
        $this->engine->setState($this->instance->getState());

        // Store each fired jobs
        $fire = function (BoxEvent $event) {
            $this->jobEvents[] = $event;
        };
        $dispatcher->addListener(BoxEvent::AFTER_JOB, $fire);
        
        // Interrupt if an End box is reached
        $end = function (GraphEvent $event) {
            $this->instance->setStatus(EngineStatus::FINISHED);
            $this->instance->save();
        };
        $dispatcher->addListener(GraphEvent::END_REACH, $end);
    }

    public function fireOutput($boxId, $output)
    {
        // apply triggers
        $this->engine->fireOutput($boxId, $output);
        
        // update instance state
        $this->instance->setState($this->engine->getState());
    }

    public function run()
    {
        // empty jobs
        $this->jobEvents = array();
        
        // run engine
        $this->engine->run();
        
        // save new state
        $this->instance->setState($this->engine->getState());
        $this->instance->save();
        
        // do jobs
        foreach ($this->jobEvents as $event) {
            $p = Payload::create([]);
            $p->instanceId = $this->instance->getId();
            $p->job = $event->getBox()->getJob();
            $p->data = $event->getData();
            $p->boxId = $event->getBox()->getId();
            $p->boxType = $event->getBox()->getType();
            $this->task->forWorker($p);
        }
    }
}
