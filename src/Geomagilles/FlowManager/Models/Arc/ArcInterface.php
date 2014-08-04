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
use Geomagilles\FlowManager\Models\Input\InputInterface;
use Geomagilles\FlowManager\Models\Output\OutputInterface;

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
     * Get Arc's input point
     * @return FlowManager\Models\Input\InputInterface
     */
    public function getInputPoint();

    /**
     * Set Arc's input point
     * @param FlowManager\Models\Input\InputInterface $input
     */
    public function setInputPoint(InputInterface $input);

    /**
     * Get Arc's output point
     * @return FlowManager\Models\Output\OutputInterface
     */
    public function getOutputPoint();

    /**
     * Set Arc's output point
     * @param FlowManager\Models\Output\OutputInterface $output
     */
    public function setOutputPoint(OutputInterface $output);

    //
    // ATTRIBUTES
    //

    /**
     * Return Arc's inputId
     * @return mixed
     */
    public function getInputId();

    /**
     * Return Arc's OutputId
     * @return mixed
     */
    public function getOutputId();

    /**
     * Set Arc's inputId
     * @param mixed $d
     */
    public function setInputId($d);

    /**
     * Set Arc's OutputId
     * @param mixed $d
     */
    public function setOutputId($d);
}
