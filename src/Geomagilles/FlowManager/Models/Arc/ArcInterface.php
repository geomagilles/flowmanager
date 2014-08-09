<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Arc;

use Geomagilles\GenericRepository\GenericRepositoryInterface;

use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Point\PointInterface;

/**
 * Interface ArcInterface
 */
interface ArcInterface extends GenericRepositoryInterface
{
    /**
     * Get Arc's graph
     * @return FlowManager\Models\Box\BoxInterface
     */
    public function getGraph();
    
    /**
     * Set Arc's graph
     * @param FlowManager\Models\Box\BoxInterface $box
     */
    public function setGraph(BoxInterface $box);

    /**
     * Get Arc's begin point
     * @return FlowManager\Models\Input\InputInterface
     */
    public function getBeginPoint();

    /**
     * Set Arc's begin point
     * @param FlowManager\Models\Input\InputInterface $input
     */
    public function setBeginPoint(PointInterface $input);

    /**
     * Get Arc's end point
     * @return FlowManager\Models\Output\OutputInterface
     */
    public function getEndPoint();

    /**
     * Set Arc's end point
     * @param FlowManager\Models\Output\OutputInterface $output
     */
    public function setEndPoint(PointInterface $output);

    //
    // ATTRIBUTES
    //

    /**
     * Get Arc's begin point id
     * @return mixed
     */
    public function getBeginPointId();

    /**
     * Set Arc's begin point id
     * @param mixed $d
     */
    public function setBeginPointId($d);

    /**
     * Get Arc's end point id
     * @return mixed
     */
    public function getEndPointId();

    /**
     * Set Arc's end point id
     * @param mixed $d
     */
    public function setEndPointId($d);
}
