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

use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;
use Geomagilles\FlowManager\Models\Arc\ArcFacade as Arc;
use Geomagilles\FlowManager\Models\Input\InputFacade as InputPoint;
use Geomagilles\FlowManager\Models\Output\OutputFacade as OutputPoint;

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
            $this->saveInputPoint($inputPoint);
        }
        // create output points
        foreach ($box->getOutputPoints() as $outputPoint) {
            $this->saveOutputPoint($outputPoint);
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
            'outputId'      => $arc->getBeginPoint()->getId(),
            'inputId'       => $arc->getEndPoint()->getId(),
        ];
        $new = Arc::create($data);
        $arc->setId($new->getId());

        return $new;
    }

    protected function saveInputPoint(PointInterface &$point)
    {
            $data = [
                'name'      => $point->getName(),
                'settings'  => json_encode($point->getSettings()),
                'boxId'     =>  $point->getBox()->getId()
            ];
            $new = InputPoint::create($data);
            $point->setId($new->getId());

            return $new;
    }

    protected function saveOutputPoint(PointInterface &$point)
    {
            $data = [
                'name'      => $point->getName(),
                'settings'  => json_encode($point->getSettings()),
                'boxId'     =>  $point->getBox()->getId()
            ];
            $new = OutputPoint::create($data);
            $point->setId($new->getId());

            return $new;
    }
}
