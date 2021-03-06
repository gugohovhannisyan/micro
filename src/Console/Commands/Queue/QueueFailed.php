<?php
/**
 * Created by IntelliJ IDEA.
 * User: gurgen
 * Date: 2018-01-22
 * Time: 11:30 AM
 */

namespace Aragil\Console\Commands\Queue;


use Aragil\Console\Command;
use Aragil\Queue\Drivers\Driver;
use Aragil\Queue\Worker\Worker;

class QueueFailed extends Command
{
    protected $description = 'Shows queues failed jobs count';

    public function handle()
    {
        $driver = Driver::make();
        $failedJobsCount = $driver->getFailedCount();

        if(empty($failedJobsCount)) {
            $this->line('No failed jobs', false);
            return;
        }

        $this->line("Failed jobs counts`");
        foreach ($failedJobsCount as $queue => $count) {
            $this->line("\t{$queue}: {$count}", false);
        }
    }
}