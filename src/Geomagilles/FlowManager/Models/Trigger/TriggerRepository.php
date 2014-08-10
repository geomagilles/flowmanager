<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Trigger;

use Geomagilles\GenericRepository\GenericRepository;

use Geomagilles\FlowManager\Models\Box\BoxInterface;
use Geomagilles\FlowManager\Models\Box\BoxFacade as Box;

/**
 * Repository of Trigger model
 */
class TriggerRepository extends GenericRepository implements TriggerInterface
{
    public function __construct(TriggerEloquent $model)
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

    //
    // ATTRIBUTES
    //

    public function getJob()
    {
        return $this->get(__FUNCTION__);
    }

    public function setJob($d)
    {
        return $this->set(__FUNCTION__, $d);
    }

    public function getEvent()
    {
        return $this->get(__FUNCTION__);
    }

    public function setEvent($d)
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
