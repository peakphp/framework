<?php
/**
 * Peak abstract Json action controller
 *
 * @author   Francois Lajoie
 * @version  $Id$
 */
abstract class Peak_Controller_Action_Json extends Peak_Controller_Action
{
    /**
     * Prepare the controller with Json render engine and proper http header
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->view->engine('Json');

        $this->view->header()->set('Content-Type: application/json');
    }

    /**
     * View vars are flushed to prevent leaking unwanted 
     * data setted outside of this controller in your Json. 
     *
     * WARNING: This have no effect if you set vars using front::postDispatch() 
     * since it will append after this. You should be carefull when adding view vars
     * in front::postDispatch() since they will be added at the end your json. 
     * A quick way to prevent this is, in your front::postDispatch(), to add a
     * conditionnal statement like this. Ex:
     *
     * public function postDispatch()
     * {
     *     if(!$this->controller instanceof Peak_Controller_Action_Json) {
     *         // do stuff
     *     }
     * }
     */
    public function dispatch()
    {
        $this->view->resetVars();
        parent::dispatch();
    }
}