<?php

namespace Peak\DebugBar\Modules\ExecutionTime;

use Peak\DebugBar\AbstractPersistentModule;

class ExecutionTime extends AbstractPersistentModule
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
     * Default storage data
     * @var array
     */
    protected $defaultStorageData = [
        'requests' => [],
        'requests_avg' => [],
        'nb_requests' => 0,
        'average_request' => 0,
        'sum_requests' => 0,
        'current_request' => ['uri' => null, 'time' => 0],
        'last_request' => ['uri' => null, 'time' => 0],
        'longest_request' => ['uri' => null, 'time' => 0],
        'shortest_request' => ['uri' => null, 'time' => 0],
    ];

    /**
     * Initialize block
     */
    public function initialize()
    {
        print_r($this->storage);
        if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $this->time = filter_var($_SERVER['REQUEST_TIME_FLOAT']);
        } elseif (isset($_SERVER['REQUEST_TIME'])) {
            $this->time = filter_var($_SERVER['REQUEST_TIME']);
        }

        if (is_numeric($this->time)) {
            $this->raw_time = microtime(true) - $this->time;
            $this->time = $this->formatDuration($this->raw_time);
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
        return $this->time;
    }

    /**
     * Format duration
     *
     * @param integer $sec
     * @return string
     */
    protected function formatDuration($sec)
    {
        $suffix = 'ms';
        $time = round($sec, 4) * 1000;
        if ($time >= 1000) {
            $time = round($time / 1000, 3);
            $suffix = 'sec';
        }
        return $time.' '.$suffix;
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

            if (!is_null($storage['current_request']['uri'])) {
                $storage['last_request'] = [
                    'uri' => $storage['current_request']['uri'],
                    'time' => $this->formatDuration($this->raw_time),
                ];
            }
            $storage['current_request'] = [
                'uri' => $request_uri,
                'time' => $this->formatDuration($this->time),
            ];
        }

        // count and sum all requests
        foreach ($storage['requests'] as $uri => $uri_times) {
            if (is_null($storage['shortest_request']['uri'])) {
                $storage['shortest_request'] = $storage['current_request'];
            }
            if (is_null($storage['longest_request']['uri'])) {
                $storage['longest_request'] = $storage['current_request'];
            }

            sort($uri_times);

            if ($uri_times[0] < $storage['shortest_request']['time']) {
                $storage['shortest_request'] = [
                    'uri' => $uri,
                    'time' => $this->formatDuration($uri_times[0])
                ];
            }

            rsort($uri_times);

            if ($uri_times[0] > $storage['longest_request']['time']) {
                $storage['longest_request'] = [
                    'uri' => $uri,
                    'time' => $this->formatDuration($uri_times[0])
                ];
            }

            $storage['nb_requests'] += count($uri_times);
            $storage['sum_requests'] += array_sum($uri_times);

            $storage['requests_avg'][$request_uri] = [
                'total' => array_sum($uri_times),
                'count' => count($uri_times),
                'average' => 0,
            ];

            $storage['requests_avg'][$request_uri]['average'] = $this->formatDuration(
                $storage['requests_avg'][$request_uri]['total'] / $storage['requests_avg'][$request_uri]['count']
            );
            $storage['requests_avg'][$request_uri]['total'] += $this->raw_time;
        }

        // average requests
        if ($storage['sum_requests'] > 0) {
            $storage['average_request'] = $this->formatDuration($storage['sum_requests'] / $storage['nb_requests']);
        }

        $this->saveToStorage($storage);
    }
}
