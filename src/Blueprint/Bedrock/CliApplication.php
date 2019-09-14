<?php

declare(strict_types=1);

namespace Peak\Blueprint\Bedrock;

interface CliApplication extends Application
{
    /**
     * @return \Symfony\Component\Console\Application
     */
    public function console(): \Symfony\Component\Console\Application;

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
