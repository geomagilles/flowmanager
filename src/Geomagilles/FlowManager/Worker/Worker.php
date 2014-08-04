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

use Log;
use Decider;
use ReflectionMethod;
use Illuminate\Foundation\Application;

use Geomagilles\FlowManager\Support\Facades\JobFacade;
use Geomagilles\FlowManager\Support\Payload\Payload;

class Worker implements WorkerInterface
{
    const SPECIAL_VARIABLE = '_';

    /**
     * Store fired triggers
     *
     * @var array
     */
    protected $firedCases = [];

    /**
     * Date of next execution
     *
     * @var Date
     */
    protected $date = null;

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

    public function follow($name = '')
    {
        $this->firedCases[] = $name;
    }

    public function wait($date)
    {
        $this->date = $date;
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
                if ($name == self::SPECIAL_VARIABLE) {
                    if ($param->isPassedByReference()) {
                        throw new \LogicException(sprintf(
                            'Special variable "$%s" can NOT be passed by reference in "%s@%s"',
                            self::SPECIAL_VARIABLE,
                            $className,
                            $methodName
                        ));
                    } else {
                        $params[] = $this;
                    }
                } else {
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
            }
            // provide instance of class
            $class = $this->createClass($className);
        } catch (\Exception $e) {
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
            return;
        }

        // fire (and updates $data)
        try {
            call_user_func_array([$class, $methodName], $params);
        } catch (\Exception $e) {
            // method failed for unknown reason => issue a warning, retry and run
            if ($this->app->runningInConsole()) {
                echo "<error>$e</error>\n";
            }
            Log::warning($e, $payload->toArray());
            Decider::jobFailed(
                $instanceId,
                $boxId,
                $e->__toString()
            );
            return;
        }
        
        // update variables passed by reference
        foreach ($r->getParameters() as $param) {
            $name = $param->getName();
            if ($param->isPassedByReference()) {
                $data[$name] = $payload_{$name};
            }
        }
        $payload->data = $data;

        // task completed
        Decider::taskCompleted(
            $instanceId,
            $boxId,
            $data,
            $this->firedCases,
            $this->date
        );
        
        if ($this->app->runningInConsole()) {
            echo "<info>  End worker job : ".$payload->job."</info>\n";
        }
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
