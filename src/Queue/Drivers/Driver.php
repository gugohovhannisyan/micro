<?php
/**
 * Created by IntelliJ IDEA.
 * User: gurgen
 * Date: 2018-01-22
 * Time: 11:43 AM
 */

namespace Aragil\Queue\Drivers;

abstract class Driver
{
    const DELIMITER = ':';
    const QUEUE_PREFIX = 'aragil-queue:';
    const QUEUE_FAILED_PREFIX = 'failed';
    const QUEUE_IN_WORK_PREFIX = 'in-work';

    const JOB_STATUS_FRESH  = 1;
    const JOB_STATUS_WORK   = 2;
    const JOB_STATUS_FAILED = 3;

    const JOB_TYPES = [
        'fresh' => [
            'type' => self::JOB_STATUS_FRESH,
            'queueName' => 'getFreshKey',
        ],
        'in-work' => [
            'type' => self::JOB_STATUS_WORK,
            'queueName' => 'getInWorkKey',
        ],
        'failed' => [
            'type' => self::JOB_STATUS_FAILED,
            'queueName' => 'getFailedKey',
        ],
    ];

    const DEFAULT_INSTANCE = 'redis';

    const INSTANCE_TYPES = [
        'redis' => RedisDriver::class
    ];

    /**
     * @return self
     */
    public static function make()
    {
        $class = self::INSTANCE_TYPES[config('queue.driver') ?? self::DEFAULT_INSTANCE] ?? self::INSTANCE_TYPES[self::DEFAULT_INSTANCE];
        return new $class;
    }

    protected function getFreshKey($queue):string
    {
        return self::QUEUE_PREFIX . self::DELIMITER . $queue;
    }
    
    protected function getInWorkKey($queue) :string
    {
        return $queue . self::DELIMITER . self::QUEUE_IN_WORK_PREFIX;
    }

    protected function getFailedKey($queue) :string
    {
        return $queue . self::DELIMITER . self::QUEUE_FAILED_PREFIX;
    }

    abstract public function addJob(\Aragil\Queue\Job\Job $job);
    abstract public function getJob($queue = null) :\Aragil\Queue\Job\Job;

    abstract public function expireJob(\Aragil\Queue\Job\Job $job, int $jobStatus = self::JOB_STATUS_FRESH & self::JOB_STATUS_WORK & self::JOB_STATUS_FAILED) :void;
    abstract public function failJob(\Aragil\Queue\Job\Job $job) :void;

    abstract public function hasJob($queue = null, int $jobStatus = self::JOB_STATUS_FRESH) :bool;

    abstract public function getFailedCount($queue = null) :int;
    abstract public function getFreshCount($queue = null) :int;
    abstract public function getInWorkCount($queue = null) :int;

    abstract public function setWorkerData(array $data):void;
    abstract public function getWorkerData():array;
}