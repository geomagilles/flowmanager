<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager;

use Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan as Artisan;

use Geomagilles\FlowManager\Console\FlowDeciderCommand;
use Geomagilles\FlowManager\Console\FlowWorkerCommand;
use Geomagilles\FlowManager\Decider\Decider;
use Geomagilles\FlowManager\Worker\Worker;
use Geomagilles\FlowManager\Tasks\Queue\QueueConnector;
use Geomagilles\FlowManager\Tasks\Sync\SyncConnector;
use Geomagilles\FlowManager\Tasks\Swf\SwfConnector;
use Geomagilles\FlowManager\Tasks\TaskManager;

class FlowManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('geomagilles/flowmanager', 'geomagilles/flowmanager');

        // commands
        
        $this->app->bind('geomagilles/flowmanager::flow.decider', function ($app) {
            return new FlowDeciderCommand();
        });
        
        $this->app->bind('geomagilles/flowmanager::flow.worker', function ($app) {
            return new FlowWorkerCommand();
        });
        
        $this->commands(array(
            'geomagilles/flowmanager::flow.decider',
            'geomagilles/flowmanager::flow.worker'
        ));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('Geomagilles\FlowManager\Worker\WorkerInterface', function ($app) {
            return new Worker($app);
        });

        $this->app->bindShared('Geomagilles\FlowManager\Decider\DeciderInterface', function ($app) {
            return new Decider($app);
        });

        $this->app->bindShared('Geomagilles\FlowManager\Tasks\TaskInterface', function ($app) {

            // Once we have an instance of the manager, we will register the various
            // resolvers for the job connectors. These connectors are responsible for
            // creating the classes that accept job configs and instantiate jobs.
            $manager = new TaskManager($app);

            $manager->addConnector('sync', function () {
                if (false !== ini_get('xdebug.max_nesting_level')) {
                    ini_set('xdebug.max_nesting_level', 5000);
                }
                
                return new SyncConnector;
            });
    
            $manager->addConnector('queue', function () {
                if ((Config::get('queue.default') == 'sync') && (false !== ini_get('xdebug.max_nesting_level'))) {
                    ini_set('xdebug.max_nesting_level', 5000);
                }
                return new QueueConnector;
            });
    
            $manager->addConnector('swf', function () {
                return new SwfConnector;
            });

            return $manager;
        });

        $this->app->bind('Geomagilles\FlowManager\Models\Arc\ArcInterface', 'Geomagilles\FlowManager\Models\Arc\ArcRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Box\BoxInterface', 'Geomagilles\FlowManager\Models\Box\BoxRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Point\PointInterface', 'Geomagilles\FlowManager\Models\Point\PointRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Instance\InstanceInterface', 'Geomagilles\FlowManager\Models\Instance\InstanceRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Trigger\TriggerInterface', 'Geomagilles\FlowManager\Models\Trigger\TriggerRepository');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'Geomagilles\FlowManager\Worker\WorkerInterface',
            'Geomagilles\FlowManager\Decider\DeciderInterface',
            'Geomagilles\FlowManager\Tasks\TaskInterface',
            'Geomagilles\FlowManager\Models\Arc\ArcInterface',
            'Geomagilles\FlowManager\Models\Box\BoxInterface',
            'Geomagilles\FlowManager\Models\Point\PointInterface',
            'Geomagilles\FlowManager\Models\Instance\InstanceInterface',
            'Geomagilles\FlowManager\Models\Trigger\TriggerInterface'
        );
    }
}
