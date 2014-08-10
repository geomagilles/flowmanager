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

use Geomagilles\GenericRepository\GenericRepository;

use Geomagilles\FlowGraph\Factory\GraphFactory;
use Geomagilles\FlowGraph\Points\PointInterface as GraphPointInterface;

use Geomagilles\FlowManager\Support\Adapters\FromStore\AdapterFromStore;
use Geomagilles\FlowManager\Support\Adapters\FromStore\AdapterFromStoreInterface;
use Geomagilles\FlowManager\Support\Adapters\ToStore\AdapterToStore;
use Geomagilles\FlowManager\Support\Adapters\ToStore\AdapterToStoreInterface;
use Geomagilles\FlowManager\Models\Point\PointEloquent;
use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;
use Geomagilles\FlowManager\Models\Arc\ArcInterface;
use Geomagilles\FlowManager\Models\Arc\ArcFacade as Arc;

/**
 * Repository of Input model
 */
class PointRepository extends GenericRepository implements PointInterface
{
    public function __construct(
        PointEloquent $model,
        AdapterToStoreInterface $toStore = null,
        AdapterFromStoreInterface $fromStore = null
    ) {
        $this->model = $model;
        $this->toStore = is_null($toStore) ? new AdapterToStore() : $toStore;
        $this->fromStore = is_null($fromStore) ? new AdapterFromStore() : $fromStore;
    }

    public function store(GraphPointInterface $point)
    {
        return $this->toStore->savePoint($point);
    }

    public function restore()
    {
        return $this->fromStore->getPoint($this);
    }

    public function isInput()
    {
        return ($this->getType() == GraphFactory::INPUT_POINT);
    }

    public function isOutput()
    {
        return ($this->getType() == GraphFactory::OUTPUT_POINT);
    }

    public function getBox()
    {
        return Box::wrap($this->model->box);
    }

    public function setBox(BoxInterface $box)
    {
        $this->model->box()->associate($box->getModel())->save();
    }

    public function getArcFrom()
    {
        return Arc::wrap($this->model->arcFrom);
    }

    public function setArcFrom(ArcInterface $arc)
    {
        $this->model->arcFrom()->associate($arc->getModel())->save();
    }

    public function getArcTo()
    {
        return Arc::wrap($this->model->arcTo);
    }

    public function setArcTo(ArcInterface $arc)
    {
        $this->model->arcTo()->associate($arc->getModel())->save();
    }

    //
    // ATTRIBUTES
    //

    public function getName()
    {
        return $this->get(__FUNCTION__);
    }

    public function setName($d)
    {
        return $this->set(__FUNCTION__, $d);
    }

    public function getType()
    {
        return $this->get(__FUNCTION__);
    }

    public function setType($d)
    {
        return $this->set(__FUNCTION__, $d);
    }

    public function getSettings()
    {
        return json_decode($this->get(__FUNCTION__), true);
    }

    public function setSettings($d)
    {
        return $this->set(__FUNCTION__, json_encode($d));
    }
}
