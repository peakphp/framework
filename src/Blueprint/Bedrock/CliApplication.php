<?php

declare(strict_types=1);

namespace Peak\Blueprint\Bedrock;

use Symfony\Component\Console\Application as ConsoleApplication;

interface CliApplication extends Application
{
    /**
     * @return ConsoleApplication
     */
    public function console(): ConsoleApplication;

    /**
     * @param mixed $command
     * @return mixed
     */
    public function add($command);

    /**
     * @return mixed
     */
    public function run();
}
