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

use Geomagilles\GenericRepository\GenericRepository;

use Geomagilles\FlowManager\Models\Point\PointInterface;
use Geomagilles\FlowManager\Models\Point\PointFacade as Point;
use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;

/**
 * Repository of Arc model
 */
class ArcRepository extends GenericRepository implements ArcInterface
{
    public function __construct(ArcEloquent $model)
    {
        $this->model = $model;
    }

    public function getGraph()
    {
        return Box::wrap($this->model->graph);
    }
    
    public function setGraph(BoxInterface $box)
    {
        $this->model->graph()->associate($box->getModel())->save();
    }

    public function getEndPoint()
    {
        return Point::wrap($this->model->end);
    }

    public function setEndPoint(PointInterface $point)
    {
        $this->model->end()->associate($point->getModel())->save();
    }

    public function getBeginPoint()
    {
        return Point::wrap($this->model->begin);
    }

    public function setBeginPoint(PointInterface $point)
    {
        $this->model->begin()->associate($point->getModel())->save();
    }

    //
    // ATTRIBUTES
    //

    public function getGraphId()
    {
        return $this->get(__FUNCTION__);
    }

    public function setGraphId($d)
    {
        return $this->set(__FUNCTION__, $d);
    }

    public function getBeginPointId()
    {
        return $this->get(__FUNCTION__);
    }

    public function setBeginPointId($d)
    {
        return $this->set(__FUNCTION__, $d);
    }

    public function getEndPointId()
    {
        return $this->get(__FUNCTION__);
    }

    public function setEndPointId($d)
    {
        return $this->set(__FUNCTION__, $d);
    }
}
