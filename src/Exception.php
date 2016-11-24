<?php
/**
 * Peak exception
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Exception extends Exception
{

    /**
     * Error constant name
     * @var string
     */
	private $_errkey;

	/**
	 * Errors messages
	 */
	const ERR_ROUTER_URI_NOT_FOUND          = 'Request uri not found.';
	const ERR_CORE_INIT_CONST_MISSING       = '%1$s is not specified (const %2$s)';
	const ERR_DEFAULT                       = 'Request failed';
	const ERR_CUSTOM                        = '%1$s';
	const ERR_CONFIG_FILE                   = 'Application configuration file format invalid';

    /**
     * Set error key constant
     *
     * @param string $errkey
     * @param string $infos
     */
    public function __construct($errkey = null, $infos = null)
	{		    	    
	    $this->_errkey = $errkey;    
	    
	    $message = $this->handleErrConstToText($errkey,$infos);	    
   
		parent::__construct($message);
	}

	/**
	 * Handle error key constants
	 *
	 * @param  integer $errkey
	 * @return string  $info
	 */
	public function handleErrConstToText($errkey = null,$infos = null)
	{ 
	    if(defined(sprintf('%s::%s', get_class($this), $errkey))) {
	        $r = constant(sprintf('%s::%s', get_class($this), $errkey));
	    }
	    else $r = self::ERR_DEFAULT;
	    
	    if(isset($infos)) {
			$r = (is_array($infos)) ? vsprintf($r,$infos) : sprintf($r,trim($infos));
	    }

		return htmlentities(strip_tags($r))."\n";
	}

	/**
	 * Get debug trace of current exception @deprecated
	 *
	 * @return string
	 */
	public function getDebugTrace()
	{
		$trace = debug_backtrace();

		$err_propagation = array();
		foreach($trace as $i => $v) {
			if(isset($v['file']) && isset($v['line'])) $err_propagation[$v['line']] = $v['file'];
		}

		$debug = 'Files:<br />';
		foreach($err_propagation as $line => $file) $debug .= '- '.$file.' (Line: '.$line.')<br />';

		if((defined('APPLICATION_ENV')) && (APPLICATION_ENV === 'development')) {
			$debug .= '<br />Trace dump ['.$this->getTime().']:<pre>';
			$debug .= print_r($trace,true);
			$debug .= '</pre>';
		}
		
		return $debug;
	}

	/**
	 * Get exception element trigger trace 
	 * 
	 * @return array
	 */
	public function getTriggerTrace()
	{
        foreach ($this->getTrace() as $frame) {
            $args = '';
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = '\'' . $arg . '\'';
                    } elseif (is_array($arg)) {
                        $args[] = 'Array';
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? 'true' : 'false';
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }   
                }   
                $args = join(', ', $args);
            }
            $frame['file'] = str_replace(APPLICATION_ABSPATH, '', $frame['file']);
            break;
        }
        return $frame;
	}
	
	public function getErrkey() { return $this->_errkey; }

	public function getLevel() { return $this->_level; }

	public function getTime() { return date('Y-m-d H:i:s'); }
}