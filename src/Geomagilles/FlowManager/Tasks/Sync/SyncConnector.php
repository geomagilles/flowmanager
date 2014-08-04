<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Tasks\Sync;

use Geomagilles\FlowManager\Tasks\ConnectorInterface;

class SyncConnector implements ConnectorInterface
{
    /**
     * Establish a task connection by sync.
     *
     * @param  array  $config
     * @return FlowManager\Tasks\Sync\SyncTask
     */
    public function connect(array $config)
    {
        return new SyncTask();
    }
}
