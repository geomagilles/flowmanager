<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Models\Point;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Input point storage
 */
class PointEloquent extends Eloquent
{
    protected $guarded = array('id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_points';

    public function box()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'box_id');
    }

    public function arcFrom()
    {
        return $this->hasOne('Geomagilles\FlowManager\Models\Arc\ArcEloquent', 'begin_point_id');
    }

    public function arcTo()
    {
        return $this->hasOne('Geomagilles\FlowManager\Models\Arc\ArcEloquent', 'end_point_id');
    }
}
