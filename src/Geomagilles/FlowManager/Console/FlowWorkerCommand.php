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

use Geomagilles\FlowManager\Tasks\TaskFacade as Task;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Console\Command;

class FlowWorkerCommand extends Command
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
    protected $description = 'Start a Geomagilles\FlowManager worker';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        Task::startWorker();
        
        $this->info('Starting worker');
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
        return array();
    }
}
