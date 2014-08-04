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

use Monolog\Handler\RavenHandler;
use Illuminate\Console\Command;
use Config;
use Raven_Client;
use App;
use Log;

abstract class FlowCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
        $this->setLogHandler();
    }

    protected function setLogHandler()
    {
        // override buffering log defined in global.php
        if (Config::has('leetix.sentry.key') && Config::has('leetix.sentry.level')) {
            $bufferHandler = new RavenHandler(
                new Raven_Client(Config::get('leetix.sentry.key')),
                Config::get('leetix.sentry.level')
            );
            App::instance('log.buffer', $bufferHandler);
            Log::getMonolog()->pushHandler($bufferHandler);
        } else {
            App::abort(500, 'Undefined Sentry configuration');
        }
    }
}
