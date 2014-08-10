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

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Trigger storage
 */
class TriggerEloquent extends Eloquent
{
    protected $guarded = array('id');

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fm_triggers';

    public function box()
    {
        return $this->belongsTo('Geomagilles\FlowManager\Models\Box\BoxEloquent', 'box_id');
    }
}
