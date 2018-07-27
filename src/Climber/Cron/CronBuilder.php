<?php

declare(strict_types=1);

namespace Peak\Climber\Cron;

use Peak\Climber\Cron\Exceptions\CronBuilderException;
use Peak\Climber\Cron\Exceptions\InvalidOptionFormatException;
use Peak\Common\TimeExpression;

/**
 * Class CronBuilder
 * @package Peak\Climber\Cron
 */
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
        'next_execution' => null,
    ];

    protected $delay = 0;

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
     * @throws InvalidOptionFormatException
     */
    public function sysCmd($sys_cmd)
    {
        if (!OptionFormat::yesNoValid($sys_cmd)) {
            throw new InvalidOptionFormatException('sys_cmd');
        }
        $this->cron['sys_cmd'] = OptionFormat::yesNo($sys_cmd);
        return $this;
    }

    /**
     * @param $repeat
     * @return $this
     * @throws InvalidOptionFormatException
     */
    public function repeat($repeat)
    {
        if (!OptionFormat::repeatValid($repeat)) {
            throw new InvalidOptionFormatException('repeat');
        }
        $this->cron['repeat'] = OptionFormat::repeat($repeat);
        return $this;
    }

    /**
     * @param $interval
     * @return $this
     * @throws \Exception
     */
    public function interval($interval)
    {
        $this->cron['interval'] = (new TimeExpression($interval))->toSeconds();
        return $this;
    }

    /**
     * @param $enabled
     * @return $this
     * @throws InvalidOptionFormatException
     */
    public function enabled($enabled)
    {
        if (!OptionFormat::yesNoValid($enabled)) {
            throw new InvalidOptionFormatException('enabled');
        }
        $this->cron['enabled'] = OptionFormat::yesNo($enabled);
        return $this;
    }

    /**
     * @param $delay
     * @return $this
     * @throws \Exception
     */
    public function delay($delay)
    {
        $this->delay = (new TimeExpression($delay))->toSeconds();
        return $this;
    }

    /**
     * Build
     * @return array
     * @throws \Exception
     */
    public function build()
    {
        if ($this->cron['repeat'] > -1 && $this->cron['interval'] === null) {
            throw new CronBuilderException('Cron interval time must be defined when repeat option is on');
        }
        if (empty($this->cron['cmd'])) {
            throw new CronBuilderException('Cron command is empty');
        }

        $this->cron['next_execution'] = time();
        if ($this->cron['interval'] !== null) {
            $this->cron['next_execution'] += $this->cron['interval'] + $this->delay;
        }

        return $this->cron;
    }
}
