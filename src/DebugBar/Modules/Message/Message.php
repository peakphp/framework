<?php

namespace Peak\DebugBar\Modules\Message;

use Peak\DebugBar\AbstractModule;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Message extends AbstractModule implements LoggerInterface
{
    use LoggerTrait;

    /**
     * Initialize module
     */
    public function initialize()
    {
        // initiate messages array
        $this->data->messages = [];
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $msg = [
            'level' => $level,
            'content' => interpolate($message, $context)
        ];

        $this->data->messages[] = (object)$msg;
    }

    /**
     * Disable message if no message
     */
    public function preRender()
    {
        if (empty($this->data->messages)) {
            $this->disableRender();
        }
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'Messages <sup>'.count($this->data->messages).'</sup>';
    }
}
