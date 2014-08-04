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

use Eloquent;

/**
 * Output point storage
 */
class OutputEloquent extends Eloquent
{
    protected $guarded = array('id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_outputs';

    public function box()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'box_id');
    }

    public function arc()
    {
        return $this->hasOne('Geomagilles\FlowManager\Models\Arc\ArcEloquent', 'output_id');
    }
}
