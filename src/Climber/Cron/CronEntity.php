<?php

namespace Peak\Climber\Cron;

use Peak\Common\TimeExpression;

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
    public function __construct(array $cron = [])
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
        $internal_name = $this->getInternalName($name);
        $method = 'get'.$internal_name;
        $value = $this->cron[$name];
        if (method_exists($this, $method)) {
            $value = $this->$method($value);
        }
        return $value;
    }

    /**
     * Get a raw value
     *
     * @param $name
     * @return mixed
     */
    public function raw($name)
    {
        return $this->cron[$name];
    }

    /**
     * Set a row field
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        $internal_name = $this->getInternalName($name);
        $method = 'set'.$internal_name;
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
    protected function getInterval($data)
    {
        if (!empty($data)) {
            return (new TimeExpression($data))->toString();
        }
    }

    /**
     * Get readable last execution
     *
     * @param $data
     * @return string
     */
    protected function getLastExecution($data)
    {
        if (!empty($data)) {
            return date('Y-m-d H:i:s', $data);
        }
    }

    /**
     * Get readable last execution
     *
     * @param $data
     * @return string
     */
    protected function getNextExecution($data)
    {
        return $this->getLastExecution($data);
    }

    /**
 * Get readable repeat status
 *
 * @param $data
 * @return string
 */
    protected function getRepeat($data)
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
    protected function getEnabled($data)
    {
        return $this->yesNo($data);
    }

    /**
     * Get readable sys command flag
     *
     * @param $data
     * @return string
     */
    protected function getSysCmd($data)
    {
        return $this->yesNo($data);
    }

    /**
     * Get readable error status
     *
     * @param $data
     * @return string
     */
    protected function getStatus($data)
    {
        if (!is_null($data)) {
            switch ($data) {
                case 1:
                    return 'success';
                default:
                    return 'fail';
            }
        }
        return 'n/a';
    }

    /**
     * Yes or no value
     *
     * @param $data
     * @return string
     */
    protected function yesNo($data)
    {
        if ($data == 0) {
            return 'no';
        }
        return 'yes';
    }

    /**
     * Get internal name of a field
     *
     * @param $name
     * @return string
     */
    protected function getInternalName($name)
    {
        $name_parts = explode('_', $name);
        $internal_name = '';
        foreach ($name_parts as $part) {
            $internal_name .= ucfirst($part);
        }
        return $internal_name;
    }
}
