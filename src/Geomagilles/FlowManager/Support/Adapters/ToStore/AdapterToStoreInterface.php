<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Support\Adapters\ToStore;

use Geomagilles\FlowGraph\Box\BoxInterface;
use Geomagilles\FlowGraph\Arc\ArcInterface;
use Geomagilles\FlowGraph\Point\PointInterface;

/**
 * Interface for adapter storing FlowManager\Graph objects
 */
interface AdapterToStoreInterface
{
    public function saveBox(BoxInterface &$box);
}
