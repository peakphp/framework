<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection\Collection;
use Peak\Common\Pipeline\Pipeline;
use Peak\Common\Pipeline\Exception\MissingPipeInterfaceException;
use Peak\Common\Pipeline\Exception\InvalidPipeException;
use Peak\Common\Pipeline\DefaultProcessor;
use Peak\Common\Pipeline\StrictProcessor;

use Peak\Di\Container;

require FIXTURES_PATH . '/pipelines/pipes.php';

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
        $container = new Container();
        $container->add(new Pipe3(new Collection(['foo' => 'bar'])));

        $processor = new DefaultProcessor($container);
        $pipeline = (new Pipeline([
            Pipe3::class
        ], $processor));

        $payload = 0;

        $payload = $pipeline->process($payload);

        $this->assertTrue($payload instanceof Collection);
        $this->assertTrue($payload instanceof Collection);
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

    /**
     * Create a simple pipeline with class using a default processor 
     */
    public function testStrictProcessorBreaking()
    {   
        $processor = new StrictProcessor(function($payload) {
            return ($payload > 5) ? false : true;
        });

        $pipeline = new Pipeline([
            function($payload) {
                $payload += 2;
                return $payload;
            },
            function($payload) {
                $payload += 4;
                return $payload;
            },
            function($payload) {
                $payload += 4;
                return $payload;
            }
        ], $processor);

        $payload = $pipeline->process(0);

        $this->assertTrue($payload == 6);
    }

    /**
     * Create a simple pipeline with class using a default processor
     */
    public function testStrictProcessor()
    {
        $processor = new StrictProcessor(function($payload) {
            return ($payload > 100) ? false : true;
        });

        $pipeline = new Pipeline([
            function($payload) {
                $payload += 2;
                return $payload;
            },
            function($payload) {
                $payload += 4;
                return $payload;
            },
            function($payload) {
                $payload += 4;
                return $payload;
            }
        ], $processor);

        $payload = $pipeline->process(0);
        $this->assertTrue($payload == 10);
    }

    /**
     * Test pipe function
     */
    public function testPipeFunction()
    {
        $pipeline = new Pipeline([
            'pipeFunction'
        ]);

        $payload = $pipeline->process(0);
        $this->assertTrue($payload == 1);
    }

    /**
     * Exceptions tests
     */
    public function testExceptions()
    {
        $error1 = false;
        try {
            $pipeline = new Pipeline([
                new Pipe14
            ]);

            $payload = $pipeline->process(1);
        } catch(MissingPipeInterfaceException $e) {
            $error1 = true;
        }

        $this->assertTrue($error1);


        $error2 = false;
        try {
            $pipeline = new Pipeline([
                'randomstring'
            ]);
            $payload = $pipeline->process(1);
        } catch(InvalidPipeException $e) {
            $error2 = true;
        }

        $this->assertTrue($error2);
    }
}
