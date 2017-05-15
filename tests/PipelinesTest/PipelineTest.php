<?php
use PHPUnit\Framework\TestCase;

use Peak\Pipelines\Pipeline;
use Peak\Pipelines\PipeInterface;
use Peak\Pipelines\DefaultProcessor;

use Peak\Di\Container;

class PipelineTest extends TestCase
{

    /**
     * Create a simple pipeline with class
     */
    public function testCreatePipelineClass()
    {   
        $payload = 0;

        $pipeline = new Pipeline();

        $payload = $pipeline->add(new Pipe1())
            ->process($payload);

        $this->assertTrue($payload == 1);

        // short syntax
        $payload = 0;

        $payload = (new Pipeline())
            ->add(new Pipe1())
            ->process($payload);

        $this->assertTrue($payload == 1);
    }

    /**
     * Create a simple pipeline with closure
     */
    public function testClosure()
    {   
        $pipeline = new Pipeline();

        $payload = 0;

        $payload = $pipeline->add(function($payload) {
            return ++$payload;
        })->add(function($payload) {
            return ++$payload;
        })->process($payload);

        $this->assertTrue($payload == 2);
    }

    /**
     * Create a simple pipeline with class
     */
    public function testPipeInterface()
    {   
        $pipeline = (new Pipeline)
            ->add(Pipe2::class);

        $payload = 0;

        $payload = $pipeline->process($payload);

        $this->assertTrue($payload == 1);
    }


    /**
     * Create a simple pipeline with class using a default processor 
     */
    public function testPipeInterfaceAndDi()
    {   
        $processor = new DefaultProcessor(new Container());

        $pipeline = (new Pipeline([], $processor))
            ->add(Pipe3::class);

        $payload = 0;

        $payload = $pipeline->process($payload);

        $this->assertTrue($payload instanceof \Peak\Common\Collection);
    }

    /**
     * Create a simple pipeline with class using a default processor 
     */
    public function testPipelineWithPipelines()
    {   
        $pipeline1 = new Pipeline([
            Pipe10::class,
            Pipe11::class
        ]);

        $pipeline2 = new Pipeline([
            $pipeline1,
            new Pipe12,
            new Pipe13
        ]);

        $payload = $pipeline2->process();
        $this->assertTrue($payload === 'ABCD');

        $pipeline3 = new Pipeline([
            $pipeline1,
            $pipeline2,
            $pipeline1,
            $pipeline2,
        ]);

        $payload = $pipeline3->process();
        $this->assertTrue($payload === 'ABABCDABABCD');
    }
}

/**
 * Pipe with invoke
 */
class Pipe1
{
    public function __invoke($payload)
    {
        return ++$payload;
    }
}

/**
 * Pipe as class
 */
class Pipe2 implements PipeInterface
{
    public function __invoke($payload)
    {
        return ++$payload;
    }
}

/**
 * Pipe as class with
 */
class Pipe3 implements PipeInterface
{
    public function __construct(\Peak\Common\Collection $coll)
    {
        $this->coll = $coll;
    }

    public function __invoke($payload)
    {
        $this->coll->payload = ++$payload;
        return $this->coll;
    }
}


/**
 * Pipe with invoke
 */
class Pipe10 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'A';
        return $payload;
    }
}

/**
 * Pipe with invoke
 */
class Pipe11 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'B';
        return $payload;
    }
}

/**
 * Pipe with invoke
 */
class Pipe12 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'C';
        return $payload;
    }
}

/**
 * Pipe with invoke
 */
class Pipe13 implements PipeInterface
{
    public function __invoke($payload)
    {
        $payload .= 'D';
        return $payload;
    }
}