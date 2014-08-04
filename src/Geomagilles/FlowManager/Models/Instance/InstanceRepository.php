<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Instance;

use Geomagilles\GenericRepository\GenericRepository;

use Geomagilles\FlowGraph\PetriGraph\Factory\PetriBoxFactory;

use Geomagilles\FlowManager\Support\Adapters\ToStore\AdapterToStoreInterface;
use Geomagilles\FlowManager\Support\Adapters\ToStore\AdapterToStore;
use Geomagilles\FlowManager\Support\Adapters\FromStore\AdapterFromStoreInterface;
use Geomagilles\FlowManager\Support\Adapters\FromStore\AdapterFromStore;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;

/**
 * Repository of Instance model
 */
class InstanceRepository extends GenericRepository implements InstanceInterface
{
    public function __construct(
        InstanceEloquent $model,
        AdapterToStoreInterface $toStore = null,
        AdapterFromStoreInterface $fromStore = null
    ) {
        $this->model = $model;
        $this->toStore = is_null($toStore) ? new AdapterToStore() : $toStore;
        $this->fromStore = is_null($fromStore) ? new AdapterFromStore() : $fromStore;
    }

    public function getGraph()
    {
        $stored = Box::wrap($this->model->graph);
        return $this->fromStore->getBox($stored);
    }

    public function dump()
    {
        $dumper = new \Geomagilles\FlowGraph\Dumper\GraphDumper();
        $graph = $this->getGraph();
        file_put_contents('flow_graph.dot', $dumper->dump($graph));
        
        $dumper = new \Geomagilles\FlowGraph\PetriGraph\Dumper\GraphDumper() ;
        $factory = new PetriBoxFactory();
        $petriGraph = $factory->create($graph);
        $petriGraph->setState($this->getState());
        file_put_contents('flow_petrigraph.dot', $dumper->dump($petriGraph));

        $dumper = new \Geomagilles\FlowGraph\PetriNet\Dumper\GraphDumper() ;
        $petriNet = $petriGraph->getPetrinet();
        file_put_contents('flow_petrinet.dot', $dumper->dump($petriNet));
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

    public function getData()
    {
        return json_decode($this->get(__FUNCTION__), true);
    }
    
    public function setData($d)
    {
        return $this->set(__FUNCTION__, json_encode($d));
    }

    public function getState()
    {
        return json_decode($this->get(__FUNCTION__), true);
    }
    
    public function setState($d)
    {
        return $this->set(__FUNCTION__, json_encode($d));
    }

    public function getStatus()
    {
        return $this->get(__FUNCTION__);
    }
    
    public function setStatus($d)
    {
        return $this->set(__FUNCTION__, $d);
    }
}
