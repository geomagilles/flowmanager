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

// php artisan aws-swf:start --workflow=GetGaAll

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Leetix\Aws\Swf\AwsSwf;

class FlowStartCommand extends FlowCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'flow:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a workflow (for test purpose)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $workflow=AwsSwf::getJobflowClass($this->option('workflow'));
        if (is_null($workflow)) {
            $this->info('unknown workflow');
        } else {
            // launch workflow
            $class=new $workflow['class']($workflow['version']);
            $opts = $class->getTestData();
            $response = $class->start(App::make('SwfClient'), $opts);
            $this->info('Workflow started: ' . json_encode($opts) . ' - runId: ' . $response->get('runId'));
        }
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
            array('workflow', 'w', InputOption::VALUE_REQUIRED, 'Name of workflow', null),
        );
    }
}
