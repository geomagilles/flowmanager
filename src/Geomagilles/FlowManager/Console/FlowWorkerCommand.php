<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Console;

// php artisan aws-swf:listen --type=decider --list=mainTaskList
// php artisan aws-swf:listen --type=activity --list=mainTaskList

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Leetix\Aws\Swf\Workflows\WorkflowListener;
use Leetix\Aws\Swf\Activities\ActivityListener;

class FlowWorkerCommand extends FlowCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'flow:worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Flow worker';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $swf = App::make('SwfClient');
        $domain = Config::get('leetix.aws.swf.domain');
        
        $this->info("Starting activity worker polling on task list: ".$this->option('list'));
        $worker = new ActivityListener($swf, $domain['name'], $this->option('list'));
        $worker->start();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('list', 'l', InputOption::VALUE_REQUIRED, 'Name of task list to listen', null),
        );
    }
}
