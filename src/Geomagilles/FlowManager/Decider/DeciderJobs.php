<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Decider;

class DeciderJobs
{
    const START_INSTANCE  = 'StartInstance';
    const RESUME_INSTANCE = 'ResumeInstance';
    const PAUSE_INSTANCE  = 'PauseInstance';
    const CANCEL_INSTANCE = 'CancelInstance';
    const TASK_TRIGGERED  = 'TaskTriggered';
    const TASK_COMPLETED  = 'TaskCompleted';
    const TASK_FAILED     = 'TaskFailed';
    const TASK_TIMEDOUT   = 'TaskTimedOut';
    const JOB_FAILED      = 'JobFailed';
}
