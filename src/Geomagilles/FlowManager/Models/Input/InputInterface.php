<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Input;

use Geomagilles\GenericRepository\GenericRepositoryInterface;

use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Arc\ArcInterface;

/**
 * Interface InputInterface
 */
interface InputInterface extends GenericRepositoryInterface
{

    /**
     * Return Input's box
     * @return FlowManager\Models\Box\BoxInterface
     */
    public function getBox();

    /**
     * Set Input's box
     * @param FlowManager\Models\Box\BoxInterface $box
     * @return self
     */
    public function setBox(BoxInterface $box);

    /**
     * Return Input's arc
     * @return FlowManager\Models\Arc\ArcInterface
     */
    public function getArc();

    /**
     * Set Input's arc
     * @param FlowManager\Models\Arc\ArcInterface $arc
     * @return self
     */
    public function setArc(ArcInterface $arc);

    //
    // ATTRIBUTES
    //

    /**
     * Return Input's name
     * @return string
     */
    public function getName();

    /**
     * Set Input's name
     * @param string $d
     */
    public function setName($d);

    /**
     * Return Input's settings
     * @return string
     */
    public function getSettings();

    /**
     * Set Input's settings
     * @param string $d
     */
    public function setSettings(array $d);
}
