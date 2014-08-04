<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Box;

use Geomagilles\GenericRepository\GenericRepositoryInterface;

use Geomagilles\FlowManager\Models\Box\BoxInterface;

/**
 * Interface BoxInterface
 */
interface BoxInterface extends GenericRepositoryInterface
{

    /**
     * Return parent's box (graph)
     * @return FlowManager\Models\Box\BoxInterface|null
     */
    public function getParentGraph();

    /**
     * Set parent's box (graph)
     * @param FlowManager\Models\Box\BoxInterface
     * @return self
     */
    public function setParentGraph(BoxInterface $graph);
    
    /**
     * Return Unit's output points
     * @return FlowManager\Models\Output\OutputInterface[]
     */
    public function getOutputs();

    /**
     * Return Unit's input points
     * @return FlowManager\Models\Input\InputInterface[]
     */
    public function getInputs();

    /**
     * Return Graph's boxes (if graph)
     * @return FlowManager\Models\Box\BoxInterface[]
     */
    public function getBoxes();

    /**
     * Return Graph's arcs (if graph)
     * @return FlowManager\Models\Arc\ArcInterface[]
     */
    public function getArcs();

    //
    // ATTRIBUTES
    //

    /**
     * Return Box's settings
     * @return mixed
     */
    public function getSettings();

    /**
     * Return Box's name
     * @return string
     */
    public function getName();

    /**
     * Set Box's settings
     * @param mixed $d
     */
    public function setSettings($d);

    /**
     * Set Box's name
     * @param string $d
     */
    public function setName($d);
}
