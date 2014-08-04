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

class FlowDeciderCommand extends FlowCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'flow:decider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a Flow decider';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $swf = App::make('SwfClient');
        $domain = Config::get('leetix.aws.swf.domain');
        
        $this->info("Starting decider worker polling on task list: ".$this->option('list'));
        $worker = new WorkflowListener($swf, $domain['name'], $this->option('list'));
        $worker->start();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['list', 'l', InputOption::VALUE_REQUIRED, 'Name of task list to listen', null],
        ];
    }
}
