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
use Geomagilles\FlowGraph\GraphInterface;
use Geomagilles\FlowGraph\PetriGraph\Factory\PetriBoxFactory;

use Geomagilles\FlowManager\Support\Adapters\FromStore\AdapterFromStore;
use Geomagilles\FlowManager\Support\Adapters\FromStore\AdapterFromStoreInterface;
use Geomagilles\FlowManager\Support\Adapters\ToStore\AdapterToStore;
use Geomagilles\FlowManager\Support\Adapters\ToStore\AdapterToStoreInterface;
use Geomagilles\FlowManager\Models\Instance\InstanceFacade as Instance;
use Geomagilles\FlowManager\Models\Point\PointFacade as Point;
use Geomagilles\FlowManager\Models\Arc\ArcFacade as Arc;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;

/**
 * Repository of Component model
 */
class BoxRepository extends GenericRepository implements BoxInterface
{
    public function __construct(
        BoxEloquent $model,
        AdapterToStoreInterface $toStore = null,
        AdapterFromStoreInterface $fromStore = null
    ) {
        $this->model = $model;
        $this->toStore = is_null($toStore) ? new AdapterToStore() : $toStore;
        $this->fromStore = is_null($fromStore) ? new AdapterFromStore() : $fromStore;
    }

    public function store(GraphInterface $graph)
    {
        return $this->toStore->saveBox($graph);
    }

    public function dump()
    {
        $dumper = new \Geomagilles\FlowGraph\Dumper\GraphDumper();
        $graph = $this->fromStore->getBox($this);
        file_put_contents('flow_graph.dot', $dumper->dump($graph));
        
        $dumper = new \Geomagilles\FlowGraph\PetriGraph\Dumper\GraphDumper() ;
        $factory = new PetriBoxFactory();
        $petriGraph = $factory->create($graph);
        file_put_contents('flow_petrigraph.dot', $dumper->dump($petriGraph));

        $dumper = new \Geomagilles\FlowGraph\PetriNet\Dumper\GraphDumper() ;
        $petriNet = $petriGraph->getPetrinet();
        file_put_contents('flow_petrinet.dot', $dumper->dump($petriNet));
    }

    public function instantiate(array $data = array(), array $state = array())
    {
        if ($this->getType() == GraphFactory::GRAPH) {
            $instance = Instance::create();
            $instance->setGraphId($this->getId());
            $instance->setData($data);
            $instance->setState($state);
            $instance->save();
            return $instance;
        }

        throw new \LogicException(sprintf('Box "%s" is not a graph, can not be instantiated', $this->getId()));
    }

    public function getParentGraph()
    {
        return Box::wrap($this->model->parentGraph);
    }

    public function setParentGraph(BoxInterface $graph)
    {
        $this->model->parentGraph()->associate($graph->getModel())->save();
    }

    public function getPoints()
    {
        return Point::wrap($this->model->points);
    }

    public function getInputs()
    {
        return Point::wrap($this->model->inputs);
    }

    public function getOutputs()
    {
        return Point::wrap($this->model->outputs);
    }

    public function getTriggers()
    {
        return Point::wrap($this->model->triggers);
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
