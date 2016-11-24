<?php
/**
 * Generetic Zendatable Authentication Model
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
abstract class Peak_Model_Authentication extends Peak_Model_Zendatable
{

    /**
     * Table name
     * MUST BE IMPLEMENTED BY CHILD CLASS
     * @var string
     */
    protected $_name;
    
    /**
     * Primary key
     * MUST BE IMPLEMENTED BY CHILD CLASS
     * @var string
     */
    protected $_primary = '';
    
    /**
     * Login database field name (user name or something like)
     * MUST BE IMPLEMENTED BY CHILD CLASS
     * @var string
     */
    protected $_login_field;
    
    /**
     * Email database field name (email field alternative to login field name)
     * Optionnal
     * @var string|null
     */
    protected $_email_field = null;
    
    /**
     * Password database field name (user name or something like)
     * MUST BE IMPLEMENTED BY CHILD CLASS
     * @var string
     */
    protected $_password_field;
    
    /**
     * Password hash algo
     * @var string or null  (null mean no hash will be perform)
     */
    protected $_password_hash_algo = null;
    
    /**
     * Password optionnal salt
     * @var string
     */
    protected $_password_salt      = '';
    
    /**
     * User result data
     * @var array
     */
    protected $_user = array(); //user array
    
    /**
     * Login result message
     * @var string
     */
    protected $_login_msg;
    
    /**
     * Login boolean
     * @var bool
     */
    protected $_login_bool = false;
    
    /**
     * Login messages constants
     */
    const MSG_DEFAULT     = 'No login attempt';
    const MSG_FAIL        = 'Login attempt failed';
    const MSG_SUCCESS     = 'Login attempt succeeded';    
    
    
    /**
     * Call parent and define default login message
     */
    public function __construct()
    {
        parent::__construct();
        $this->_login_msg = self::MSG_DEFAULT;
    }
    
    /**
     * Try to login a user
     *
     * @param  string $login
     * @param  string $pass
     * @param  bool   $hash_and_salt_pass
     * @return bool
     */
    public function login($login, $pass, $hash_and_salt_pass = true)
    {
        //reset login status and user in case of multiple attempt
        $this->_login_bool = false;
        $this->_user = array();
        
        //hash and salt the password if needed and specified(@see $_password_hash_algo)
        if($hash_and_salt_pass === true && !is_null($this->_password_hash_algo)) {
            $pass = $this->hasStr($pass, true);
        }
        
        $select = 'SELECT * FROM `'.$this->_name.'` WHERE ';
        
        //check if login is an email
        if(!is_null($this->_email_field) && filter_var($login, FILTER_VALIDATE_EMAIL) !== false) {
            $login_field = $this->_email_field;
        }
        else $login_field = $this->_login_field;
            
        $select .= ' `'.$login_field.'` = '.$this->_db->quote($login).' AND `'.$this->_password_field.'` = "'.$pass.'" LIMIT 1';
        
        //query the database
        $result = $this->query($select)->fetch();
        
        //we got a result
        if(!empty($result)) {
            
            //push result data to $_user
            $this->_user = $result;
            $this->_login_msg =  self::MSG_SUCCESS;
            $this->_login_bool = true;
        }
        else {
            $this->_login_msg =  self::MSG_FAIL;
            $this->_login_bool = false;
        }
        
        return $this->isLogged();       
    }
    
    /**
     * Get login status bool
     *
     * @return bool
     */
    public function isLogged()
    {
        return $this->_login_bool;
    }
    
    /**
     * Get login message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->_login_msg;
    }
    
    /**
     * Get current logged user if exists
     *
     * @return array
     */
    public function getUser()
    {
        return $this->_user;
    }
    
    /**
     * Return password salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->_password_salt;
    }

    /**
     * Generate a hash for a password string
     *
     * @param  string  $str      
     * @param  boolean $add_salt Mean that salt we be added to the string before hashing
     *
     * @return string
     */
    public function hashStr($str, $add_salt = false)
    {
        $hash = '';

        // we got hash algo
        if(!is_null($this->_password_hash_algo)) {

            // add salt at the end
            if($add_salt) $str .= $this->_password_salt;
            $hash = hash($this->_password_hash_algo, $str);
        }
        else $hash = $str;

        return $hash;
    }
    
}