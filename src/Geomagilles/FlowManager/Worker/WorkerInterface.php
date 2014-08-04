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

/**
 * Interface for boxes.
 */
interface WorkerInterface
{
    /**
     * Fire case by name
     * @param string $name
     */
    public function follow($name = '');

    /**
     * Wait (delay in seconds or until a date)
     * 
     * @param int|DateTime $date 
     * @return void
     */
    public function wait($date);
}
