<?php

namespace Peak\Climber\Cron;

use Peak\Common\TimeExpression;
use \ArrayIterator;
use \IteratorAggregate;

class CronEntity
{
    /**
     * @var array
     */
    protected $cron = [];

    /**
     * Constructor
     * @param array $cron
     */
    public function __construct(array $cron)
    {
        $this->cron = $cron;
    }

    /**
     * Get a row field
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        $value = $this->cron[$name];
        $method = 'field'.ucfirst($name);
        if (method_exists($this, $method)) {
            $value = $this->$method($value);
        }
        return $value;
    }

    /**
     * Get readable interval
     *
     * @param $data
     * @return string
     */
    protected function fieldInterval($data)
    {
        if(!empty($data)) {
            $readable_data = (new TimeExpression($data))->toString();
            if ($readable_data === $data.' secs') {
                return $data;
            }
            return $readable_data.' ('.$data.' secs)';
        }
    }

    /**
     * Get readable last execution
     *
     * @param $data
     * @return string
     */
    protected function fieldLast_execution($data)
    {
        if (!empty($data)) {
            return (new TimeExpression($data))->toDate();
        }
    }

    /**
     * Get readable last execution
     *
     * @param $data
     * @return string
     */
    protected function fieldNext_execution($data)
    {
        return $this->fieldLast_execution($data);
    }

    /**
 * Get readable repeat status
 *
 * @param $data
 * @return string
 */
    protected function fieldRepeat($data)
    {
        if ($data == -1) {
            return 'no';
        } elseif ($data == 0) {
            return 'always';
        }
        return $data.' time'.(($data > 1) ? 's' : '');
    }

    /**
     * Get readable enabled status
     *
     * @param $data
     * @return string
     */
    protected function fieldEnabled($data)
    {
        if ($data == 0) {
            return 'no';
        }
        return 'yes';
    }
}
