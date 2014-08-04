<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Box;

use Geomagilles\GenericRepository\GenericRepository;

use Geomagilles\FlowGraph\Factory\GraphFactory;

use Geomagilles\FlowManager\Models\Instance\InstanceFacade as Instance;
use Geomagilles\FlowManager\Models\Input\InputFacade as InputPoint;
use Geomagilles\FlowManager\Models\Output\OutputFacade as OutputPoint;
use Geomagilles\FlowManager\Models\Arc\ArcFacade as Arc;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;

/**
 * Repository of Component model
 */
class BoxRepository extends GenericRepository implements BoxInterface
{
    public function __construct(BoxEloquent $model)
    {
        $this->model = $model;
    }

    public function createInstance(array $data = array(), array $state = array())
    {
        if ($this->getType() == GraphFactory::GRAPH) {
            $instance = Instance::create();
            $instance->setGraphId($this->getId());
            $instance->setData($data);
            $instance->setState($state);
            $instance->save();
            return $instance;
        }

        throw new \LogicException(sprintf('Box "%s" is not a graph', $this->getId()));
    }

    public function getParentGraph()
    {
        return Box::wrap($this->model->parentGraph);
    }

    public function setParentGraph(BoxInterface $graph)
    {
        $this->model->parentGraph()->associate($graph->getModel())->save();
    }

    public function getOutputs()
    {
        return OutputPoint::wrap($this->model->outputs);
    }

    public function getInputs()
    {
        return InputPoint::wrap($this->model->inputs);
    }

    public function getBoxes()
    {
        return Box::wrap($this->model->boxes);
    }

    public function getArcs()
    {
        return Arc::wrap($this->model->arcs);
    }

    //
    // ATTRIBUTES
    //

    public function getParentGraphId()
    {
        return $this->get(__FUNCTION__);
    }

    public function setParentGraphId($d)
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

    public function getName()
    {
        return $this->get(__FUNCTION__);
    }

    public function setName($d)
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

    public function getJob()
    {
        return $this->get(__FUNCTION__);
    }

    public function setJob($d)
    {
        return $this->set(__FUNCTION__, $d);
    }
}
