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

use Geomagilles\FlowGraph\Factory\GraphFactory;
use Geomagilles\FlowGraph\Box\BoxInterface;
use Geomagilles\FlowGraph\Arc\ArcInterface;
use Geomagilles\FlowGraph\Point\PointInterface;
use Geomagilles\FlowGraph\Point\InputPoint\InputPointInterface;
use Geomagilles\FlowGraph\Point\OutputPoint\OutputPointInterface;
use Geomagilles\FlowGraph\Point\TriggerPoint\TriggerPointInterface;

use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;
use Geomagilles\FlowManager\Models\Arc\ArcFacade as Arc;
use Geomagilles\FlowManager\Models\Point\PointFacade as Point;

/**
 * Adapter storing FlowManager\Graph objects
 */
class AdapterToStore implements AdapterToStoreInterface
{
    public function saveBox(BoxInterface &$box)
    {
        // save box
        $data = [
            'name'          => $box->getName(),
            'type'          => $box->getType(),
            'settings'      => $box->getSettings(),
        ];
        if ($box->hasJob()) {
            $data['job'] = $box->getJob();
        }
        if (! is_null($box->getParentGraph())) {
            $data['parentGraphId'] = $box->getParentGraph()->getId();
        }
        $new = Box::create($data);
        $box->setId($new->getId());

        // create input points
        foreach ($box->getInputPoints() as $inputPoint) {
            $this->savePoint($inputPoint);
        }
        // create output points
        foreach ($box->getOutputPoints() as $outputPoint) {
            $this->savePoint($outputPoint);
        }
        // create trigger points
        foreach ($box->getTriggerPoints() as $triggerPoint) {
            $this->savePoint($triggerPoint);
        }

        // save recursively if Graph
        if ($box->isGraph()) {
            foreach ($box->getBoxes() as $b) {
                $this->saveBox($b);
            }
            foreach ($box->getArcs() as $arc) {
                $a = $this->saveArc($arc);
                $a->setGraph($new);
            }
        }

        return $new;
    }

    protected function saveArc(ArcInterface &$arc)
    {
        $data = [
            'beginPointId' => $arc->getBeginPoint()->getId(),
            'endPointId'   => $arc->getEndPoint()->getId(),
        ];
        $new = Arc::create($data);
        $arc->setId($new->getId());

        return $new;
    }

    public function savePoint(PointInterface &$point)
    {
        $data = array();
        if ($point->isInput()) {
            $data['type'] = GraphFactory::INPUT_POINT;
        } elseif ($point->isOutput()) {
            $data['type'] = GraphFactory::OUTPUT_POINT;
        } elseif ($point->isTrigger()) {
            $data['type'] = GraphFactory::TRIGGER_POINT;
            $data['settings'] = json_encode($point->getSettings());
        } else {
            throw new \Exception("Unknown point type");
        }
        $data['name'] = $point->getName();
        $data['boxId'] = $point->getBox()->getId();

        $new = Point::create($data);
        $point->setId($new->getId());

        return $new;
    }
}
