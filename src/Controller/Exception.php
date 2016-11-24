<?php
/**
 * Controller Exception
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_Controller_Exception extends Peak_Exception
{
    
    const ERR_CTRL_NOT_FOUND                = 'Application controller %1$s not found.';
    const ERR_CTRL_ACTION_NOT_FOUND         = 'Controller action \'%1$s\' not found in %2$s.';
    const ERR_CTRL_DEFAULT_ACTION_NOT_FOUND = 'Controller action method by default not found.';
    const ERR_CTRL_HELPER_NOT_FOUND         = 'Controller helper \'%1$s\' not found.';
	const ERR_CTRL_ACTION_PARAMS_MISSING    = '%1$s argument(s) missing for action \'%2$s\'';
	
}