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

use Eloquent;

/**
 * Box storage
 */
class BoxEloquent extends Eloquent
{

    protected $guarded = array('id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_boxes';

    // parent box
    public function parentGraph()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'parent_graph_id');
    }

    // child boxes
    public function boxes()
    {
        return $this->hasMany('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'parent_graph_id');
    }

    // output points
    public function outputs()
    {
        return $this->hasMany('Geomagilles\FlowManager\Models\Output\OutputEloquent', 'box_id');
    }

    // input points
    public function inputs()
    {
        return $this->hasMany('Geomagilles\FlowManager\Models\Input\InputEloquent', 'box_id');
    }

    // arcs
    public function arcs()
    {
        return $this->hasMany('Geomagilles\FlowManager\Models\Arc\ArcEloquent', 'graph_id');
    }
}
