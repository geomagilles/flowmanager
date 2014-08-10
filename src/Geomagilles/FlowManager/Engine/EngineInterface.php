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

/**
 * Interface EngineInterface
 */
interface EngineInterface
{
    /**
     * Run engine
     * @return void
     */
    public function run();

    /**
     * Fire output
     * @param mixed $boxId
     * @param string $output
     */
    public function fireOutput($boxId, $output);
}
