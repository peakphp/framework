<?php

namespace Peak\Providers\Laravel;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container as Container;
use Illuminate\Events\Dispatcher as Dispatcher;

use \PDOException;
use \Exception;

class Database
{
    /**
     * The current database connection
     * @var \Illuminate\Database\*
     */
    protected $db;

    /**
     * The current database connection name
     * @var string
     */
    protected $connection_name;

    /**
     * The current database schema
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;

    /**
     * Capsule manager
     * @var \Illuminate\Database\Schema\Manager
     */
    protected static $capsule;

    /**
     * Constructor
     *
     * @param array  $conf Database configuration
     * @param string $name Connection name
     */
    public function __construct($conf, $name = 'default')
    {
        $this->connect($conf, $name);
    }
    
    /**
     * Connect to database
     *
     * @param  array $conf
     * @throws Exception
     */
    protected function connect($conf, $name = 'default')
    {
        // prepare capsule only once
        if (!isset(self::$capsule)) {
            self::$capsule = new Capsule;
        }

        try {
            self::$capsule->addConnection($conf, $name);
            self::$capsule->setEventDispatcher(new Dispatcher(new Container));
            self::$capsule->bootEloquent();
            self::$capsule->setAsGlobal();

            // store the connection
            $this->db = self::$capsule->getConnection($name);

            // store the connection name
            $this->connection_name = $name;

            // store the schema
            $this->schema = self::$capsule->schema($name);
        } catch (PDOException $e) {
            throw new Exception('Can\'t connect to database');
        }
    }

    /**
     * Call unknown method directly on $db object
     *
     * @param  string $method
     * @param  mixed  $args
     * @return mixed
     */
    public function __call($method, $args = null)
    {
        return call_user_func_array([$this->db, $method], $args);
    }

    /**
     * Schema
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    public function schema()
    {
        return $this->schema;
    }

    /**
     * Get current connection name
     *
     * @return string
     */
    public function getConnectionName()
    {
        return $this->connection_name;
    }
}
