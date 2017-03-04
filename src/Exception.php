<?php

namespace Peak;

/**
 * Peak exception
 */
class Exception extends \Exception
{

    /**
     * Error constant name
     * @var string
     */
    private $errkey;

    /**
     * Errors messages
     */
    const ERR_ROUTER_URI_NOT_FOUND          = 'Request uri not found.';
    const ERR_CORE_INIT_CONST_MISSING       = '%1$s is not specified (const %2$s)';
    const ERR_DEFAULT                       = 'Request failed';
    const ERR_CUSTOM                        = '%1$s';
    const ERR_CONFIG_FILE                   = 'Application configuration file format invalid';

    const ERR_APP_ENV_MISSING               = 'Application configuration \'env\' is missing';
    
    const ERR_VIEW_ENGINE_NOT_SET           = 'View rendering engine not set. Use engine() from Peak_View before trying to render application controller.';
    const ERR_VIEW_ENGINE_NOT_FOUND         = 'View rendering engine \'%1$s\' not found.';
    const ERR_VIEW_HELPER_NOT_FOUND         = 'View helper \'%1$s\' not found.';
    const ERR_VIEW_SCRIPT_NOT_FOUND         = 'View script file %1$s not found.';
    const ERR_VIEW_FILE_NOT_FOUND           = 'View file \'%1$s\' not found.';
    const ERR_VIEW_THEME_NOT_FOUND          = 'View theme \'%1$s\' folder not found.';
    
    const ERR_CTRL_NOT_FOUND                = 'Application controller %1$s not found.';
    const ERR_CTRL_ACTION_NOT_FOUND         = 'Controller action \'%1$s\' not found in %2$s.';
    const ERR_CTRL_DEFAULT_ACTION_NOT_FOUND = 'Controller action method by default not found.';
    const ERR_CTRL_HELPER_NOT_FOUND         = 'Controller helper \'%1$s\' not found.';
    const ERR_CTRL_ACTION_PARAMS_MISSING    = '%1$s argument(s) missing for action \'%2$s\'';
    

    /**
     * Set error key constant
     *
     * @param string $errkey
     * @param string $infos
     */
    public function __construct($errkey = null, $infos = null)
    {                   
        $this->errkey = $errkey;    
        
        $message = $this->handleErrConstToText($errkey,$infos);     
   
        parent::__construct($message);
    }

    /**
     * Handle error key constants
     *
     * @param  integer $errkey
     * @return string  $info
     */
    public function handleErrConstToText($errkey = null, $infos = null)
    { 
        if (defined(sprintf('%s::%s', get_class($this), $errkey))) {
            $r = constant(sprintf('%s::%s', get_class($this), $errkey));
        }
        else $r = self::ERR_DEFAULT;
        
        if (isset($infos)) {
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

        $content = $this->getMessage();
        $content .= '['.$this->getErrkey().' / '.$this->getTime()."]\n";
        $content .= str_replace('/path/to/code/', '', $this->getTraceAsString());

        if (!isCli()) $content = '<pre>'.$content.'</pre>';
        return $content;
    }

    /**
     * Print the debug trace
     */
    public function printDebugTrace()
    {
        print_r($this->getDebugTrace());
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
    
    public function getErrkey() 
    {
        return $this->errkey; 
    }

    public function getTime() 
    { 
        return date('Y-m-d H:i:s'); 
    }
}
