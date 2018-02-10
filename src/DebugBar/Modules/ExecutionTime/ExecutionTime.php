<?php

namespace Peak\DebugBar\Modules\ExecutionTime;

use Peak\DebugBar\AbstractModule;
use Peak\Common\Session;

class ExecutionTime extends AbstractModule
{
    /**
     * @var mixed
     */
    protected $time = 'n/a';

    /**
     * @var integer
     */
    protected $raw_time = 0;

    /**
     * @var string
     */
    protected $suffix = 'ms';

    /**
     * Initialize block
     */
    public function initialize()
    {

        if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $this->time = filter_var($_SERVER['REQUEST_TIME_FLOAT']);
        } elseif (isset($_SERVER['REQUEST_TIME'])) {
            $this->time = filter_var($_SERVER['REQUEST_TIME']);
        }

        if (is_numeric($this->time)) {
            $this->raw_time = $this->time;
        }

        if (is_numeric($this->time)) {
            $this->time = round(microtime(true) - $this->time, 4) * 1000;

            if ($this->time >= 1000) {
                $this->time = round($this->time / 1000, 3);
                $this->suffix = 'sec';
            }
        }

        // check if we have an active session to store page execution times
        if (Session::isStarted()) {

            // chronometer
//            if (isset($_SERVER['REQUEST_URI'])) {
//                $request_uri = filter_var($_SERVER['REQUEST_URI']);
//
//                $request_chronos = [];
//                if (isset($_SESSION['pkdebugbar']['chronometers'][$request_uri])) {
//                    $request_chronos = unserialize($_SESSION['pkdebugbar']['chronometers'][$request_uri]);
//                }
//
//                $request_chronos[] = $this->raw_time;
//                $_SESSION['pkdebugbar']['chronometers'][$request_uri] = serialize($request_chronos);
//            }
        }

//        $nb_chrono = count($chronos);
//        $sum_chrono = array_sum($chronos);
//        $average_chrono = $sum_chrono / $nb_chrono;
//        sort($chronos);
//        $short = $chronos[0];
//        rsort($chronos);
//        $long = $chronos[0];
//        echo 'Number of requests: '.$nb_chrono.'<br />';
//        echo 'Average: '.round($average_chrono,2).'ms / request<br /><br />';
//        echo 'Fastest request: '.$short.'ms<br />';
//        echo 'Longest request: '.$long.'ms<br /><br />';

    }

    protected function buildStats()
    {
        if (!Session::isStarted()) {
            return;
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = filter_var($_SERVER['REQUEST_URI']);

            $request_chronos = [];
            if (isset($_SESSION['pkdebugbar']['chronometers'][$request_uri])) {
                $request_chronos = unserialize($_SESSION['pkdebugbar']['chronometers'][$request_uri]);
            }

            $request_chronos[] = $this->raw_time;
            $_SESSION['pkdebugbar']['chronometers'][$request_uri] = serialize($request_chronos);
        }
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return $this->time.' '.$this->suffix;
    }

}
