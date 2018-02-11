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
     * Raw time in seconds
     * @var integer
     */
    protected $raw_time = 0;

    /**
     * @var string
     */
    protected $suffix = 'ms';


    protected $default_storage_data = [
        'requests' => [],
        'nb_requests' => 0,
        'average_request' => 0,
        'sum_requests' => 0,
        'current_request' => [],
        'last_request' => [],
        'longest_request' => [],
        'shortest_request' => [],
    ];

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
            $this->raw_time = microtime(true) - $this->time;
        }

        if (is_numeric($this->time)) {
            $this->time = round($this->raw_time, 4) * 1000;

            if ($this->time >= 1000) {
                $this->time = round($this->time / 1000, 3);
                $this->suffix = 'sec';
            }
        }

        $this->data->time = $this->time;
        $this->buildStats();
        $this->data->stats = $this->getStorage();
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

    /**
     * Build various stats
     */
    protected function buildStats()
    {
        $storage = $this->getStorage();

        // requests, current_request and last_request
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = filter_var($_SERVER['REQUEST_URI']);
            $storage['requests'][$request_uri][] = $this->raw_time;

            if (!empty($storage['current_request'])) {
                $storage['last_request'] = [
                    'uri' => $storage['current_request']['uri'],
                    'time' => $storage['current_request']['time'],
                ];
            }
            $storage['current_request'] = [
                'uri' => $request_uri,
                'time' => $this->raw_time,
            ];
        }

        // count and sum all requests
        foreach ($storage['requests'] as $uri => $uri_times) {
            if (empty($storage['shortest_request'])) {
                $storage['shortest_request'] = $storage['current_request'];
            }
            if (empty($storage['longest_request'])) {
                $storage['longest_request'] = $storage['current_request'];
            }

            sort($storage['requests'][$uri]);

            if ($storage['requests'][$uri][0] < $storage['shortest_request']['time']) {
                $storage['shortest_request'] = [
                    'uri' => $uri,
                    'time' => $storage['requests'][$uri][0]
                ];
            }

            rsort($storage['requests'][$uri]);

            if ($storage['requests'][$uri][0] > $storage['longest_request']['time']) {
                $storage['longest_request'] = [
                    'uri' => $uri,
                    'time' => $storage['requests'][$uri][0]
                ];
            }

            $storage['nb_requests'] += count($uri_times);
            $storage['sum_requests'] += array_sum($uri_times);
        }

        // average requests
        $storage['average_request'] = $storage['sum_requests'] / $storage['nb_requests'];

        $this->saveToStorage($storage);
    }
}
