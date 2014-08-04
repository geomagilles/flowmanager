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

use App;
use Config;

use Aws\Swf\SwfClient;
use Aws\Swf\Exception\DomainAlreadyExistsException;
use Aws\Swf\Exception\TypeAlreadyExistsException;

class FlowRegisterCommand extends FlowCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'flow:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register Amazon Simple Workflows\' domain, workflows & activities';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Client
        $swf = App::make('SwfClient');

        // Domain
        $domain = Config::get('leetix.aws.swf.domain');
        
        // Domain registering
        $this->info($this->registerDomain($swf, $domain));
        
        // Register Workflows
        foreach (Config::get('leetix.aws.swf.workflow.workflows') as $workflow) {
            $vars = [
                'domain' => $domain['name'],
                'name' => $workflow['name'],
                'version' => $workflow['version'],
                'description' => $workflow['description']
            ];
            // possible options
            $opts = [
                'defaultTaskList',
                'defaultTaskStartToCloseTimeout',
                'defaultExecutionStartToCloseTimeout',
                'defaultChildPolicy'
            ];
            foreach ($opts as $var) {
                $vars[$var] = isset($workflow[$var]) ? $workflow[$var] : $this->getDefaultConfig('workflow', $var);
            }
            $this->info($this->registerWorkflow($swf, $vars));
        }

        // Register Activities
        foreach (Config::get('leetix.aws.swf.activity.activities') as $activity) {
            $vars = [
                'domain' => $domain['name'],
                'name' => $activity['name'],
                'version' => $activity['version'],
                'description' => $activity['description']
            ];
            // possible options
            $opts = [
                'defaultTaskList',
                'defaultTaskHeartbeatTimeout',
                'defaultTaskScheduleToStartTimeout',
                'defaultTaskStartToCloseTimeout',
                'defaultTaskScheduleToCloseTimeout',
            ];
            foreach ($opts as $var) {
                $vars[$var] = isset($activity[$var]) ? $activity[$var] : $this->getDefaultConfig('activity', $var);
            }
            $this->info($this->registerActivity($swf, $vars));
        }
    }

    /**
     * Return $type's default config for $name
     *
     * @return boolean
     */
    protected function getDefaultConfig($type, $name)
    {
        if (Config::has('leetix.aws.swf.'.$type.'.defaults.'.$name)) {
            return Config::get('leetix.aws.swf.'.$type.'.defaults.'.$name);
        } else {
            App::abort(500, 'Unknown config for leetix.aws.swf.'.$type.'.defaults.'.$var);
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
        return array();
    }

    private function registerDomain(SwfClient $swf, $domain)
    {
        try {
            $swf->registerDomain($domain);
            return 'Domain '.$domain['name'].' SUCCESSfully registered';
        } catch (DomainAlreadyExistsException $e) {
            // if the domain already exists, it's safe to ignore that error.
            return 'Domain '.$domain['name'].' already registered';
        }
    }
    
    private function registerWorkflow(SwfClient $swf, $vars)
    {
        try {
            $swf->registerWorkflowType($vars);
            return 'Workflow '.$vars['name'].' v'.$vars['version'].' SUCCESSfully registered';
        } catch (TypeAlreadyExistsException $e) {
            // if the workflow already exists, it's safe to ignore that error.
            return 'Workflow '.$vars['name'].' v'.$vars['version'].' already registered';
        }
    }
    
    private function registerActivity(SwfClient $swf, $vars)
    {
        try {
            $swf->registerActivityType($vars);
            return 'Activity '.$vars['name'].' v'.$vars['version'].' SUCCESSfully registered';
        } catch (TypeAlreadyExistsException $e) {
            // if the activity already exists, it's safe to ignore that error.
            return 'Activity '.$vars['name'].' v'.$vars['version'].' already registered';
        }
    }
}
