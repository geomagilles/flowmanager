<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Output;

use Geomagilles\GenericRepository\GenericRepository;

use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;
use Geomagilles\FlowManager\Models\Arc\ArcInterface;
use Geomagilles\FlowManager\Models\Arc\ArcFacade as Arc;

/**
 * Repository of Output model
 */
class OutputRepository extends GenericRepository implements OutputInterface
{
    public function __construct(OutputEloquent $model)
    {
        $this->model = $model;
    }

    public function getBox()
    {
        return Box::wrap($this->model->box);
    }

    public function setBox(BoxInterface $box)
    {
        $this->model->box()->associate($box->getModel())->save();
    }

    public function getArc()
    {
        return Arc::wrap($this->model->arc);
    }

    public function setArc(ArcInterface $arc)
    {
        $this->model->arc()->associate($arc->getModel())->save();
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

    public function getSettings()
    {
        return json_decode($this->get(__FUNCTION__), true);
    }

    public function setSettings(array $d)
    {
        return $this->set(__FUNCTION__, json_encode($d));
    }
}
