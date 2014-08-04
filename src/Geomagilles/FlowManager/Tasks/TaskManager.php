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

use Config;
use Geomagilles\FlowManager\Support\Driver\DriverManager;

class TaskManager extends DriverManager implements TaskManagerInterface
{
    /**
     * Get the job connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    public function getConfig($name)
    {
        return Config::get("geomagilles/flowmanager::connections.{$name}");
    }

    /**
     * Get the name of the default job connection.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return Config::get('geomagilles/flowmanager::default');
    }
}
