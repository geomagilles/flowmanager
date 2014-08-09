<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Worker;

use Queue;

use ReflectionMethod;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log as Log;
use Illuminate\Support\Facades\Artisan as Artisan;

use Geomagilles\FlowGraph\Factory\GraphFactory;

use Geomagilles\FlowManager\Decider\DeciderFacade as Decider;
use Geomagilles\FlowManager\Support\Facades\JobFacade;
use Geomagilles\FlowManager\Support\Payload\Payload;

class Worker
{
    const SPECIAL_VARIABLE = '_';

    /**
     * Create a new Worker.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

   /**
     * Parse the job declaration into class and method.
     *
     * @param  Payload  $payload
     * @return void
     */
    public function fire(Payload $payload)
    {
        if ($this->app->runningInConsole()) {
            echo "<info>  Begin worker job : ".$payload->job."</info>\n";
        }
        $instanceId = $payload->instanceId;
        $boxId = $payload->boxId;
        $boxType = $payload->boxType;
        $data = $payload->data;

        // init call
        try {
            // resolve
            list($className, $methodName) = $this->parse($payload->job);
            // dependency injection
            $params = [];
            // dependency injection
            $r = new ReflectionMethod($className, $methodName);
            foreach ($r->getParameters() as $param) {
                $name = $param->getName();
                
                if (isset($data[$name])) {
                    $payload_{$name} = $data[$name];
                    if ($param->isPassedByReference()) {
                        $params[] = &$payload_{$name};
                    } else {
                        $params[] = $payload_{$name};
                    }
                } else {
                    if ($param->isPassedByReference()) {
                        $params[] = &$payload_{$name};
                    } else {
                        throw new \LogicException(sprintf(
                            'Unknown variable "%s" in "%s@%s" (a new variable MUST be passed by reference)',
                            $name,
                            $className,
                            $methodName
                        ));
                    }
                }
            }
            // provide instance of class
            $class = $this->createClass($className);
        } catch (\Exception $e) {
            return $this->taskFailed($instanceId, $boxId, $payload, $e);
        }

        // fire, updates $data and get result
        try {
            $output = call_user_func_array([$class, $methodName], $params);
        } catch (\Exception $e) {
            return $this->jobFailed($instanceId, $boxId, $payload, $e);
        }
        
        // update variables passed by reference
        foreach ($r->getParameters() as $param) {
            $name = $param->getName();
            if ($param->isPassedByReference()) {
                $data[$name] = $payload_{$name};
            }
        }
        $payload->data = $data;

        //
        if ($boxType == GraphFactory::TASK) {
            $output = is_null($output) ? '' : $output;
            if (is_string($output)) {
                Decider::taskCompleted(
                    $instanceId,
                    $boxId,
                    $data,
                    $output,
                    null
                );
            } else {
                $e = new InvalidReturnException(sprintf('Method "%s@%s" MUST return an output\'s name', $className, $methodName));
                $this->taskFailed($instanceId, $boxId, $payload, $e);
            }
        } elseif ($boxType == GraphFactory::WAIT) {
            if (is_integer($output) || ($output instanceof \DateTime)) {
                Decider::taskCompleted(
                    $instanceId,
                    $boxId,
                    $data,
                    '',
                    $output
                );
            } elseif (is_null($output)) {
                // nothing to do, waiting for a trigger
            } else {
                $e = new InvalidReturnException(sprintf('Method "%s@%s" MUST return null (wait forever), a duration (in seconds) or a date', $className, $methodName));
                $this->taskFailed($instanceId, $boxId, $payload, $e);
            }
        } else {
            throw new \Exception("Unknown box type");
        }

        if ($this->app->runningInConsole()) {
            echo "<info>  End worker job : ".$payload->job."</info>\n";
        }
    }

// method failed for unknown reason => issue a warning, retry and run
            

    /**
     * In case of a recoverable error.
     *
     * @param  string  $job
     * @return array
     */
    private function jobFailed($instanceId, $boxId, $payload, $e)
    {
        // configuration error => issue an error, retry and run
        if ($this->app->runningInConsole()) {
            echo "<error>$e</error>\n";
        }
        Log::warning($e, $payload->toArray());
        Decider::jobFailed(
            $instanceId,
            $boxId,
            $e->__toString()
        );
    }

    /**
     * In case of unrecoverable error.
     *
     * @param  string  $job
     * @return array
     */
    private function taskFailed($instanceId, $boxId, $payload, $e)
    {
        // configuration error => issue an error, retry and pause
        if ($this->app->runningInConsole()) {
            echo "<error>$e</error>\n";
        }
        Log::error($e, $payload->toArray());
        Decider::taskFailed(
            $instanceId,
            $boxId,
            $e->__toString()
        );
    }

    /**
     * Parse the job declaration into instance and method.
     *
     * @param  string  $job
     * @return array
     */
    private function parse($job)
    {
        $segments = explode('@', $job);
        return count($segments) > 1 ? $segments : [$segments[0], 'fire'];
    }

   /**
     * Create an instance of $className.
     *
     * @param  string  $className
     * @return Object
     */
    private function createClass($className)
    {
        return $this->app->make($className);
    }
}
