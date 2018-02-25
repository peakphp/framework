<?php

declare(strict_types=1);

namespace Peak\Common;

/**
 * Wrap Collection / DotNotation abilities around php $_SESSION
 */
class Session extends DotNotationCollection
{
    /**
     * We pass $_SESSION by reference so we can alter it through the collection
     */
    public function __construct()
    {
        if (self::isStarted()) {
            $this->items =& $_SESSION;
        }
    }

    /**
     * Check if session is started
     *
     * @return bool
     */
    public static function isStarted(): bool
    {
        return (session_status() == PHP_SESSION_ACTIVE);
    }

    /**
     * Start session
     *
     * @param null $name
     * @param array $options
     * @return Session
     */
    public static function start(string $name = null, array $options = []): Session
    {
        session_name($name);
        session_start($options);

        return new self();
    }

    /**
     * Destroy session
     */
    public static function destroy(): void
    {
        if (self::isStarted()) {
            session_destroy();
        }
    }
}
