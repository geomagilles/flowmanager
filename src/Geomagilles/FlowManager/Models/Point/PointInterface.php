<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Point;

use Geomagilles\GenericRepository\GenericRepositoryInterface;

use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Arc\ArcInterface;

/**
 * Repository of Input model
 */
interface PointInterface extends GenericRepositoryInterface
{
    public function getBox();

    public function setBox(BoxInterface $box);

    public function getArcFrom();

    public function setArcFrom(ArcInterface $arc);

    public function getArcTo();

    public function setArcTo(ArcInterface $arc);

    //
    // ATTRIBUTES
    //

    public function getName();

    public function setName($d);

    public function getSettings();

    public function setSettings($d);
}
