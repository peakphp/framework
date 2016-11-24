<?php
/**
 * Controller Exception
 * 
 * @author   Francois Lajoie
 * @version  $Id$
 */
class Peak_View_Exception extends Peak_Exception
{
    const ERR_VIEW_ENGINE_NOT_SET   = 'View rendering engine not set. Use engine() from Peak_View before trying to render application controller.';
    const ERR_VIEW_ENGINE_NOT_FOUND = 'View rendering engine \'%1$s\' not found.';
    const ERR_VIEW_HELPER_NOT_FOUND = 'View helper \'%1$s\' not found.';
    const ERR_VIEW_SCRIPT_NOT_FOUND = 'View script file %1$s not found.';
    const ERR_VIEW_FILE_NOT_FOUND   = 'View file \'%1$s\' not found.';
    const ERR_VIEW_THEME_NOT_FOUND  = 'View theme \'%1$s\' folder not found.';
}