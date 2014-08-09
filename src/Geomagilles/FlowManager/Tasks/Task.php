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

use Geomagilles\FlowManager\Decider\DeciderInterface;
use Geomagilles\FlowManager\Worker\WorkerInterface;
use Geomagilles\FlowManager\Support\Driver\Driver;
use Illuminate\Encryption\Encrypter;

abstract class Task extends Driver implements TaskInterface
{
    /**
     * Fire a job for a worker.
     * 
     * @param mixed $payload
     * @return mixed job_id
     */
    abstract public function forWorker($payload);

    /**
     * Fire a job for a decider.
     * 
     * @param mixed $payload
     * @return mixed job_id
     */
    abstract public function forDecider($payload, $date = null);

    /**
     * Set the encrypter instance.
     *
     * @param  \Illuminate\Encryption\Encrypter  $crypt
     * @return self
     */
    public function setEncrypter(Encrypter $crypt)
    {
        $this->crypt = $crypt;

        return $this;
    }

    /**
     * Convert $payload to json
     *
     * @param  mixed $payload
     * @return string
     */
    protected function toJson($payload)
    {
        if (is_array($payload)) {
            return json_encode($payload);
        }
        return $payload->toJson();
    }
}
