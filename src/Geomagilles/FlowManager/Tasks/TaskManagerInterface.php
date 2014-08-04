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

use Geomagilles\FlowManager\Support\Payload\Payload;

interface TaskManagerInterface
{
    /**
     * Returns config for $name connection
     *
     * @param  string  $name
     * @return array 
     */
    public function getConfig($name);

    /**
     * Returns default driver
     *
     * @return string 
     */
    public function getDefaultDriver();
}
