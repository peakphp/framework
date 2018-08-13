<?php

declare(strict_types=1);

namespace Peak\Pipelines;

use Psr\Container\ContainerInterface;

/**
 * Class StrictProcessor
 * @package Peak\Pipelines
 */
class StrictProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * Verification closure
     * @var callable
     */
    protected $check;

    /**
     * Constructor
     *
     * @param callable $check closure returning a boolean
     * @param ContainerInterface|null $container Di container to resolve pipe class
     */
    public function __construct(callable $check, ContainerInterface $container = null)
    {
        parent::__construct($container);
        $this->check = $check;
    }

    /**
     * Process pipes
     *
     * @param array $pipes
     * @param mixed $payload
     * @return mixed
     * @throws Exception\InvalidPipeException
     * @throws Exception\MissingPipeInterfaceException
     */
    public function process(array $pipes, $payload)
    {
        foreach ($pipes as $pipe) {
            $payload = $this->resolvePipe($pipe, $payload);

            // if check fail, break the pipeline right here and return the payload
            if (call_user_func($this->check, $payload) === false) {
                return $payload;
            }
        }

        return $payload;
    }
}
