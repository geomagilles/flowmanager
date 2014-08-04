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

interface ConnectorInterface
{
    /**
     * Establish a job connection.
     *
     * @param  array  $config
     * @return FlowManager\Tasks\TaskInterface
     */
    public function connect(array $config);
}
