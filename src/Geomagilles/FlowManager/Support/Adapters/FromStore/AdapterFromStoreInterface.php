<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Support\Adapters\FromStore;

use Geomagilles\FlowManager\Models\Box\BoxInterface;

/**
 * Interface for adapter retrieving FlowManager\Graph objects
 */
interface AdapterFromStoreInterface
{
    public function getBox(BoxInterface $box);
}
