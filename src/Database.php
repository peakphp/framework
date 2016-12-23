<?php
namespace Peak;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container as Container;
use Illuminate\Events\Dispatcher as Dispatcher;

class Database
{
    /**
     * The current database connection
     * @var Capsule
     */
    protected static $_db;

    /**
     * Return current database connection
     * 
     * @return object
     */
    static function db()
    {
        return self::$_db;
    }

    /**
     * Connect to database
     * 
     * @param  array $conf   
     */
    static function connect($conf)
    {
        try {
            $db = new Capsule;
            $db->addConnection($conf);
            $db->setEventDispatcher(new Dispatcher(new Container));
            $db->bootEloquent();
            $db->setAsGlobal();

            self::$_db = $db;
        }
        catch(PDOException $e) {

            throw new \Exception('Can\'t connect to database');
        }
    }

    /**
     * Return table (shortcut of self::db()->table('table'))
     */
    static function table($table_name)
    {
        return self::db()->table($table_name);
    }

    /**
     * Schema
     * @return  Return schema (shortcut of self::db()->schema())
     */
    static function schema()
    {
        return self::db()->schema()
    }

    /**
     * Set PDO fetch mode to array assoc
     */
    static function setFetchModeToAssoc()
    {
        self::db()->setFetchMode(\PDO::FETCH_ASSOC);
    }

    /**
     * Set PDO fetch mode to class objet
     */
    static function setFetchModeToClass()
    {
        self::db()->setFetchMode(\PDO::FETCH_CLASS);
    }

    /**
     * Return true or false
     * 
     * @return boolean
     */
    static function dbCheck($table = null)
    {
        try {
            if(!is_object(self::db())) return false;
            self::db()->schema();
            if(isset($table)) {
                return self::db()->schema()->hasTable($table);
            }
            return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }
}