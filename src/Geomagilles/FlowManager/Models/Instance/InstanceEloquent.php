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

use Eloquent;

/**
 * Instance storage
 */
class InstanceEloquent extends Eloquent
{
    protected $guarded = array('id', 'flow_id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_instances';

    public function graph()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'graph_id');
    }
}
