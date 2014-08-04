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

use Geomagilles\FlowManager\Models\Input\InputInterface;
use Geomagilles\FlowManager\Models\Input\InputFacade as InputPoint;
use Geomagilles\FlowManager\Models\Output\OutputInterface;
use Geomagilles\FlowManager\Models\Output\OutputFacade as OutputPoint;
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

    public function getInputPoint()
    {
        return InputPoint::wrap($this->model->input);
    }

    public function setInputPoint(InputInterface $input)
    {
        $this->model->input()->associate($input->getModel())->save();
    }

    public function getOutputPoint()
    {
        return OutputPoint::wrap($this->model->output);
    }

    public function setOutputPoint(OutputInterface $output)
    {
        $this->model->output()->associate($output->getModel())->save();
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

    public function getInputId()
    {
        return $this->get(__FUNCTION__);
    }

    public function setInputId($d)
    {
        return $this->set(__FUNCTION__, $d);
    }

    public function getOutputId()
    {
        return $this->get(__FUNCTION__);
    }

    public function setOutputId($d)
    {
        return $this->set(__FUNCTION__, $d);
    }
}
