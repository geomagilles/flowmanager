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

use Eloquent;

/**
 * Arc storage
 */
class ArcEloquent extends Eloquent
{
    protected $guarded = array('id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_arcs';

    public function graph()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'graph_id');
    }

    public function input()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Input\InputEloquent', 'input_id');
    }

    public function output()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Output\OutputEloquent', 'output_id');
    }
}
