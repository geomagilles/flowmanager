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

use Geomagilles\FlowManager\Models\Point\PointInterface;
use Geomagilles\FlowManager\Models\Box\BoxInterface;

use Geomagilles\FlowGraph\Factory\GraphFactoryInterface;
use Geomagilles\FlowGraph\Factory\GraphFactory;

/**
 * Adapter retrieving FlowManager\Graph objects
 */
class AdapterFromStore implements AdapterFromStoreInterface
{
    public function __construct(GraphFactoryInterface $factory = null)
    {
        $this->factory = is_null($factory) ? new GraphFactory() : $factory;
    }

    public function getBox(BoxInterface $box)
    {
        // get Box or Graph
        $new = $this->factory->newObject($box->getType(), $box->getName());
        $new->setId($box->getId());

        // add job if any
        if (! is_null($box->getJob())) {
            $new->setJob($box->getJob());
        }

        // add settings
        $new->setSettings($box->getSettings());
        
        // add input points
        foreach ($box->getInputPoints() as $point) {
            $p = $this->factory->createInputPoint($point->getName());
            $p->setId($point->getId());
            $new->addInputPoint($p);
        }

        // add output points
        foreach ($box->getOutputPoints() as $point) {
            $p = $this->factory->createOutputPoint($point->getName());
            $p->setId($point->getId());
            $new->addOutputPoint($p);
        }

        // if Graph: get recursively
        if ($new->isGraph()) {
            // get boxes
            foreach ($box->getBoxes() as $b) {
                $new->addBox($this->getBox($b));
            }

            // get arcs
            foreach ($box->getArcs() as $arc) {
                // find begin point
                $beginPoint = $arc->getBeginPoint();
                $beginBox = $new->getBox($beginPoint->getBox()->getName());
                if ($beginPoint->isOutput()) {
                    $beginPoint = $beginBox->getOutputPoint($beginPoint->getName());
                } else {
                    throw new \Exception("Unable to find begin point");
                }

                // find end point
                $endPoint = $arc->getEndPoint();
                $endBox   = $new->getBox($endPoint->getBox()->getName());
                if ($endPoint->isInput()) {
                    $endPoint = $endBox->getInputPoint($endPoint->getName());
                } else {
                    throw new \Exception("Unable to find end point");
                }

                // create and add arc
                $a = $this->factory->createArc($beginPoint, $endPoint);
                $a->setId($arc->getId());
                $new->addArc($a);
            }
        }

        return $new;
    }
}
