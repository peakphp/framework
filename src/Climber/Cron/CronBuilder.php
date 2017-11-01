<?php

namespace Peak\Climber\Cron;

use Peak\Climber\Cron\Exceptions\CronBuilderException;
use Peak\Climber\Cron\Exceptions\InvalidDatabaseConfigException;
use Peak\Common\TimeExpression;

class CronBuilder
{
    /**
     * @var array
     */
    protected $cron = [
        'name' => '',
        'cmd' => '',
        'sys_cmd' => 0,
        'repeat' => -1,
        'interval' => null,
        'enabled' => '1',
    ];

    /**
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->cron['name'] = $name;
        return $this;
    }

    /**
     * @param $cmd
     * @return $this
     */
    public function cmd($cmd)
    {
        $this->cron['cmd'] = $cmd;
        return $this;
    }

    /**
     * @param $sys_cmd
     * @return $this
     * @throws InvalidDatabaseConfigException
     */
    public function sysCmd($sys_cmd)
    {
        if (!OptionFormat::yesNoValid($sys_cmd)) {
            throw new InvalidDatabaseConfigException('sys_cmd');
        }
        $this->cron['sys_cmd'] = OptionFormat::yesNo($sys_cmd);
        return $this;
    }

    /**
     * @param $repeat
     * @return $this
     * @throws InvalidDatabaseConfigException
     */
    public function repeat($repeat)
    {
        if (!OptionFormat::repeatValid($repeat)) {
            throw new InvalidDatabaseConfigException('repeat');
        }
        echo 'VALID';
        $this->cron['repeat'] = OptionFormat::repeat($repeat);
        return $this;
    }

    /**
     * @param $interval
     * @return $this
     */
    public function interval($interval)
    {
        $this->cron['interval'] = (new TimeExpression($interval))->toSeconds();
        return $this;
    }

    /**
     * @param $enabled
     * @return $this
     * @throws InvalidDatabaseConfigException
     */
    public function enabled($enabled)
    {
        if (!OptionFormat::yesNoValid($enabled)) {
            throw new InvalidDatabaseConfigException('enabled');
        }
        $this->cron['enabled'] = OptionFormat::yesNo($enabled);
        return $this;
    }

    /**
     * Build
     * @return array
     * @throws \Exception
     */
    public function build()
    {
        if($this->cron['repeat'] > -1 && $this->cron['interval'] === null) {
            throw new CronBuilderException('Cron interval time must be defined when repeat option is on');
        }
        if(empty($this->cron['cmd'])) {
            throw new CronBuilderException('Cron command is empty');
        }
        return $this->cron;
    }
}
