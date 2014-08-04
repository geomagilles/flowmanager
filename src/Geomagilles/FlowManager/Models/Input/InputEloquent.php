<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Input;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Input point storage
 */
class InputEloquent extends Eloquent
{
    protected $guarded = array('id', 'component_id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_inputs';

    public function box()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'box_id');
    }

    public function arc()
    {
        return $this->hasOne('Geomagilles\FlowManager\Models\Arc\ArcEloquent', 'input_id');
    }
}
