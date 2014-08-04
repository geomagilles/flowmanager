<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Instance;

use Geomagilles\GenericRepository\GenericRepositoryInterface;

/**
 * Interface InstanceInterface
 */
interface InstanceInterface extends GenericRepositoryInterface
{
    /**
     * Return Flow's graph
     * @return FlowGraph\GraphInterface
     */
    public function getGraph();

    //
    // ATTRIBUTES
    //
   
    /**
     * Return Instance's graph id
     * @return mixed
     */
    public function getGraphId();

    /**
     * Set Instance's graph id
     * @param mixed $d
     */
    public function setGraphId($d);

    /**
     * Return Instance's data
     * @return string
     */
    public function getData();

    /**
     * Set Instance's data
     * @param array $d
     */
    public function setData($d);

    /**
     * Return Instance's state
     * @return string
     */
    public function getState();

    /**
     * Set Instance's state
     * @param array $d
     */
    public function setState($d);

    /**
     * Return Instance's status
     * @return string
     */
    public function getStatus();

    /**
     * Set Instance's status
     * @param string $d
     */
    public function setStatus($d);
}
