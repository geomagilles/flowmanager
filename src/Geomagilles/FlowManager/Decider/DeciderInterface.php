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

/**
 * Interface for boxes.
 */
interface DeciderInterface
{
    public function taskFailed($instanceId, $boxId, $exception, $date = null);

    public function taskCompleted($instanceId, $boxId, $data, $output, $date = null);
    
    public function startInstance($graphId, $data = [], $date = null);
    
    public function pauseInstance($instanceId, $date = null);
    
    public function resumeInstance($instanceId, $date = null);
    
    public function killInstance($instanceId, $date = null);
}
