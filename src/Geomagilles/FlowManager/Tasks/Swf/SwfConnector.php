<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Tasks\Swf;

use Geomagilles\FlowManager\Tasks\ConnectorInterface;

class SwfConnector implements ConnectorInterface
{
    /**
     * Establish a task connection by swf.
     *
     * @param  array  $config
     * @return FlowManager\Tasks\Swf\SwfTask
     */
    public function connect(array $config)
    {
        $swf = SwfClient::factory($config);

        return new SwfTask($swf, $config['job']);
    }
}
