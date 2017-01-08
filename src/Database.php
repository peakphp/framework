<?php
namespace Peak;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container as Container;
use Illuminate\Events\Dispatcher as Dispatcher;

class Database
{
    /**
     * The current database connection
     * @var Illuminate\Database\*
     */
    protected $db;

    /**
     * The current database schema
     * @var Illuminate\Database\Schema\*
     */
    protected $schema;

    /**
     * Capsule manager
     * @var Illuminate\Database\Schema\Manager
     */
    protected static $capsule;


    /**
     * Constructor
     * @param array
     */
    public function __construct($conf, $name = 'default')
    {
        $this->connect($conf, $name);
    }
    
    /**
     * Connect to database
     * 
     * @param  array $conf   
     */
    protected function connect($conf, $name = 'default')
    {
        // prepare capsule only once
        if(!isset(self::$capsule)) {
            self::$capsule = new Capsule;
        }

        try {
            self::$capsule->addConnection($conf, $name);
            self::$capsule->setEventDispatcher(new Dispatcher(new Container));
            self::$capsule->bootEloquent();
            self::$capsule->setAsGlobal();

            // store the connection
            $this->db = self::$capsule->getConnection($name);

            // store the schema
            $this->schema = self::$capsule->schema($name);
        }
        catch(PDOException $e) {
            throw new \Exception('Can\'t connect to database');
        }
    }

    /**
     * Call unknow method directly on $db object
     * 
     * @param  string $method 
     * @param  mixed $args   
     * @return mixed         
     */
    public function  __call($method, $args = null)
    {
        return call_user_func_array([$this->db, $method], $args);
    }


    /**
     * Schema
     * @return  Return schema (shortcut of self::db()->schema())
     */
    public function schema()
    {
        return $this->schema;
    }

    /**
     * Set PDO fetch mode to array assoc
     */
    public function setFetchModeToAssoc()
    {
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
    }

    /**
     * Set PDO fetch mode to object class
     */
    public function setFetchModeToClass()
    {
        $this->db->setFetchMode(\PDO::FETCH_CLASS);
    }

}