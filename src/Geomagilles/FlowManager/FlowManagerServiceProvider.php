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

use Illuminate\Support\ServiceProvider;

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
                return new SyncConnector;
            });
    
            $manager->addConnector('queue', function () {
                return new QueueConnector;
            });
    
            $manager->addConnector('swf', function () {
                return new SwfConnector;
            });

            return $manager;
        });

        $this->app->bind('Geomagilles\FlowManager\Models\Arc\ArcInterface', 'Geomagilles\FlowManager\Models\Arc\ArcRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Box\BoxInterface', 'Geomagilles\FlowManager\Models\Box\BoxRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Input\InputInterface', 'Geomagilles\FlowManager\Models\Input\InputRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Output\OutputInterface', 'Geomagilles\FlowManager\Models\Output\OutputRepository');

        $this->app->bind('Geomagilles\FlowManager\Models\Instance\InstanceInterface', 'Geomagilles\FlowManager\Models\Instance\InstanceRepository');
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
            'Geomagilles\FlowManager\Models\Input\InputInterface',
            'Geomagilles\FlowManager\Models\Output\OutputInterface',
            'Geomagilles\FlowManager\Models\Instance\InstanceInterface'
        );
    }
}
