<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeomagillesFlowmanagerCreateCanceledTimersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up($name = null)
    {
        Schema::create($this->getTableName($name), function (Blueprint $table) {
            $table->increments('id');
            $table->string('timer_task_id')->index();
            $table->date('timer_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down($name = null)
    {
        Schema::drop($this->getTableName($name));
    }

    /**
     * Get table name
     *
     * @return void
     */
    private function getTableName($name)
    {
        return is_null($name) ? 'fm_canceled_timers' : $name;
    }
}
