<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Decider;

use Illuminate\Foundation\Application;

use Geomagilles\FlowGraph\Components\Task\TaskInterface;
use Geomagilles\FlowManager\Support\Payload\Payload;
use Geomagilles\FlowManager\Engine\Engine;
use Geomagilles\FlowManager\Engine\EngineStatus;

class Decider implements DeciderInterface
{
    /**
     * Create a new Decider.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return self
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->task = $app->make('Geomagilles\FlowManager\Tasks\TaskInterface');
        $this->instance = $app->make('Geomagilles\FlowManager\Models\Instance\InstanceInterface');
    }

    /**
     * Choose which method to fire according to provided job
     *
     * @param  Geomagilles\FlowManager\Support\Payload\Payload $payload
     * @return void
     */
    public function fire(Payload $payload)
    {
        if ($this->app->runningInConsole()) {
            echo "<info>  Begin decider job : ".$payload->job."</info>\n";
        }
        // resolve
        switch ($payload->job) {
            case DeciderJobs::TASK_COMPLETED:
                $this->fireTaskCompleted($payload);
                break;
            case DeciderJobs::TASK_TRIGGERED:
                $this->fireTaskTriggered($payload);
                break;
            case DeciderJobs::TASK_FAILED:
                $this->fireTaskFailed($payload);
                break;
            case DeciderJobs::JOB_FAILED:
                $this->fireJobFailed($payload);
                break;
            case DeciderJobs::START_INSTANCE:
                $this->fireStartInstance($payload);
                break;
            case DeciderJobs::PAUSE_INSTANCE:
                $this->firePauseInstance($payload);
                break;
            case DeciderJobs::RESUME_INSTANCE:
                $this->fireResumeInstance($payload);
                break;
            case DeciderJobs::KILL_INSTANCE:
                $this->fireKillInstance($payload);
                break;
            default:
                throw new \LogicException(sprintf('Unknown Decider Job "%s"', $payload->job));
                break;
        };

        if ($this->app->runningInConsole()) {
            echo "<info>  End decider job : ".$payload->job."</info>\n";
        }
    }
    
    public function taskCompleted($instanceId, $boxId, $data, $firedCases = [], $date = null)
    {
        $this->task->forDecider([
            'job'        => DeciderJobs::TASK_COMPLETED,
            'instanceId' => $instanceId,
            'boxId'      => $boxId,
            'data'       => $data,
            'firedCases' => $firedCases
        ], $date);
    }

    public function taskTriggered($instanceId, $boxId, $firedTriggers = [], $date = null)
    {
        $this->task->forDecider([
            'job'           => DeciderJobs::TASK_TRIGGERED,
            'instanceId'    => $instanceId,
            'boxId'         => $boxId,
            'firedTriggers' => $firedTriggers
        ], $date);
    }

    public function taskFailed($instanceId, $boxId, $exception, $date = null)
    {
        $this->task->forDecider([
            'job'        => DeciderJobs::TASK_FAILED,
            'instanceId' => $instanceId,
            'boxId'      => $boxId,
            'exception'  => $exception
        ], $date);
    }

    public function jobFailed($instanceId, $boxId, $exception, $date = null)
    {
        $this->task->forDecider([
            'job'        => DeciderJobs::JOB_FAILED,
            'instanceId' => $instanceId,
            'boxId'      => $boxId,
            'exception'  => $exception
        ], $date);
    }

    public function startInstance($instanceId, $data = [], $date = null)
    {
        $this->task->forDecider([
            'job'    => DeciderJobs::START_INSTANCE,
            'instanceId' => $instanceId,
            'data'   => $data
        ], $date);
    }

    public function pauseInstance($instanceId, $date = null)
    {
        $this->task->forDecider([
            'job'        => DeciderJobs::PAUSE_INSTANCE,
            'instanceId' => $instanceId
        ], $date);
    }

    public function resumeInstance($instanceId, $date = null)
    {
        $this->task->forDecider([
            'job'        => DeciderJobs::RESUME_INSTANCE,
            'instanceId' => $instanceId
        ], $date);
    }

    public function killInstance($instanceId, $date = null)
    {
        $this->task->forDecider([
            'job'        => DeciderJobs::KILL_INSTANCE,
            'instanceId' => $instanceId
        ], $date);
    }

    private function fireTaskCompleted(Payload $payload)
    {
        $this->execTaskCompleted(
            $payload->instanceId,
            $payload->boxId,
            $payload->data,
            $payload->firedCases
        );
    }

    private function fireTaskTriggered(Payload $payload)
    {
        $this->execTaskTriggered(
            $payload->instanceId,
            $payload->boxId,
            $payload->firedCases
        );
    }

    private function fireTaskFailed(Payload $payload)
    {
        $this->execTaskFailed(
            $payload->instanceId,
            $payload->boxId,
            $payload->exception
        );
    }

    private function fireJobFailed(Payload $payload)
    {
        $this->execJobFailed(
            $payload->instanceId,
            $payload->boxId,
            $payload->exception
        );
    }

    private function fireStartInstance(Payload $payload)
    {
        $this->execStartInstanceId(
            $payload->instanceId,
            $payload->data
        );
    }

    private function firePauseInstance(Payload $payload)
    {
        $this->execPauseInstanceId(
            $payload->instanceId
        );
    }

    private function fireResumeInstance(Payload $payload)
    {
        $this->execResumeInstanceId(
            $payload->instanceId
        );
    }

    private function fireKillInstance(Payload $payload)
    {
        $this->execKillInstanceId(
            $payload->instanceId
        );
    }

    private function execTaskCompleted($instanceId, $boxId, $data, $firedCases)
    {
        // Retrieve instance
        $instance = $this->instance->getById($instanceId);
         // set data
        $instance->setData($data);
        // Create a new engine for this instance
        $engine = new Engine($instance, $this->task);
        // Fire triggers
        $engine->fireTriggers($boxId, count($firedCases)==0 ? [''] : $firedCases);
        // Save new data & state
        $instance->save();
        // Run
        if ($instance->getStatus() == EngineStatus::RUNNING) {
            $engine->run();
        }
    }

    private function execTaskTriggered($instanceId, $boxId, $firedTriggers)
    {
        // Retrieve instance
        $instance = $this->instance->getById($instanceId);
        // Create a new engine for this instance
        $engine = new Engine($instance, $this->task);
        // Fire triggers
        $engine->fireTriggers($boxId, count($firedTriggers)==0 ? [''] : $firedTriggers);
        // Save new data & state
        $instance->save();
        // Run
        if ($instance->getStatus() == EngineStatus::RUNNING) {
            $engine->run();
        }
    }

    private function execTaskFailed($instanceId, $boxId, $exception)
    {
        // Retrieve instance
        $instance = $this->instance->getById($instanceId);
        // Create a new engine for this instance
        $engine = new Engine($instance, $this->task);
        // Fire triggers
        $engine->fireTriggers($boxId, [TaskInterface::OUTPUT_RETRY]);
        // Save new state
        $instance->save();
        // pause this instance
        $this->execPauseInstance($instance);
    }

    private function execJobFailed($instanceId, $boxId, $exception)
    {
        // Retrieve instance
        $instance = $this->instance->getById($instanceId);
        // Create a new engine for this instance
        $engine = new Engine($instance, $this->task);
        // Fire triggers
        $engine->fireTriggers($boxId, [TaskInterface::OUTPUT_RETRY]);
        // Save new data & state
        $instance->save();
        // Run
        if ($instance->getStatus() == EngineStatus::RUNNING) {
            $engine->run();
        }
    }

    private function execStartInstanceId($instanceId, $data)
    {
        $this->execStartInstance($this->instance->getById($instanceId), $data);
    }

    private function execStartInstance($instance, $data)
    {
        // set data
        $instance->setData($data);
        // set status
        $instance->setStatus(EngineStatus::RUNNING);
        // Save new data & status
        $instance->save();
        // Create a new engine for this instance
        $engine = new Engine($instance, $this->task);
        // run this instance
        $engine->run();
    }

    private function execPauseInstanceId($instanceId)
    {
        $this->execPauseInstance($this->instance->getById($instanceId));
    }

    private function execPauseInstance($instance)
    {
        // check status
        if ($instance->getStatus() == EngineStatus::RUNNING) {
            // set status
            $instance->setStatus(EngineStatus::PAUSED);
            // Save new status
            $instance->save();
        }
    }

    private function execResumeInstanceId($instanceId)
    {
        $this->execResumeInstance($this->instance->getById($instanceId));
    }

    private function execResumeInstance($instance)
    {
        if ($instance->getStatus() == EngineStatus::PAUSED) {
            // set status
            $instance->setStatus(EngineStatus::RUNNING);
            // Save new status
            $instance->save();
            // Create a new engine for this instance
            $engine = new Engine($instance, $this->task);
            // run this instance
            $engine->run();
        }
    }

    private function execKillInstanceId($instanceId)
    {
        $this->execKillInstance($this->instance->getById($instanceId));
    }

    private function execKillInstance($instance)
    {
        // check status
        if ($instance->getStatus() != EngineStatus::FINISHED) {
            // set status
            $instance->setStatus(EngineStatus::KILLED);
            // Save new status
            $instance->save();
        }
    }
}
