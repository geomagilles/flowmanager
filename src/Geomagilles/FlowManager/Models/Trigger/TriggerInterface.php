<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Trigger;

use Geomagilles\GenericRepository\GenericRepositoryInterface;

use Geomagilles\FlowManager\Models\Box\BoxInterface;

/**
 * Interface TriggerInterface
 */
interface TriggerInterface extends GenericRepositoryInterface
{
    /**
     * Get trigger's box
     * @return FlowManager\Models\Box\BoxInterface
     */
    public function getBox();
    
    /**
     * Set trigger's box
     * @param FlowManager\Models\Box\BoxInterface $box
     */
    public function setBox(BoxInterface $box);

    //
    // ATTRIBUTES
    //

    /**
     * Return trigger's job
     * @return string
     */
    public function getJob();

    /**
     * Set trigger's job
     * @param string $d
     */
    public function setJob($d);

    /**
     * Return trigger's event
     * @return string
     */
    public function getEvent();

    /**
     * Set trigger's event
     * @param string $d
     */
    public function setEvent($d);

    /**
     * Return trigger's settings
     * @return mixed
     */
    public function getSettings();

    /**
     * Set trigger's settings
     * @param mixed $d
     */
    public function setSettings($d);
}
