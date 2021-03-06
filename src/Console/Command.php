<?php
/**
 * Created by IntelliJ IDEA.
 * User: gurgen
 * Date: 2017-12-06
 * Time: 4:54 PM
 */

namespace Aragil\Console;


use Aragil\Console\Commands\Database\Seed;
use Aragil\Console\Commands\Help;
use Aragil\Console\Commands\Database\Migrate;
use Aragil\Console\Commands\Queue\QueueCount;
use Aragil\Console\Commands\Queue\QueueFailed;
use Aragil\Console\Commands\Queue\QueueInWork;
use Aragil\Console\Commands\Queue\QueueWork;
use Aragil\Console\Commands\Queue\RequeueFailedJobs;
use Aragil\Router\Route;

abstract class Command
{
    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @param $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @param $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @param $argument
     * @param $default
     * @return mixed
     */
    public function arguments($argument = null, $default = null)
    {
        return $this->get('arguments', $argument, $default);
    }

    /**
     * @param $option
     * @param $default
     * @return mixed
     */
    public function options($option = null, $default = null)
    {
        return $this->get('options', $option, $default);
    }

    /**
     * @return string
     */
    public final function getDescription()
    {
        return $this->description;
    }

    /**
     * @return void
     */
    public static final function loadDefaultRoutes()
    {
        Route::console('', Help::class);
        Route::console('help', Help::class);
        Route::prefix('migrate', function () {
            Route::console('{db}', Migrate::class);
        });
        Route::prefix('seed', function () {
            Route::console('{db}', Seed::class);
        });
        Route::prefix('queue', function () {
            Route::console('work', QueueWork::class);
            Route::console('count', QueueCount::class);
            Route::console('failed-count', QueueFailed::class);
            Route::console('in-work-count', QueueInWork::class);
            Route::console('requeue-failed-jobs', RequeueFailedJobs::class);
        });
    }

    /**
     * @param $text
     * @param bool $withDate
     */
    protected function line($text, $withDate = true)
    {
        if($withDate) {
            $text = '[' . date('Y-m-d H:i:s') . '] ' . $text;
        }

        echo $text . PHP_EOL;
    }

    /**
     * @param $text
     */
    protected function error($text)
    {
        $this->line($text);
        die(PHP_EOL);
    }

    /**
     * @param $fromWhere
     * @param $key
     * @param $default
     * @return mixed
     */
    private function get($fromWhere, $key, $default)
    {
        if(is_null($key)) {
            return $this->{$fromWhere};
        }
        return $this->{$fromWhere}[$key] ?? $default;
    }
}