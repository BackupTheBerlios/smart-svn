<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Public test module event handler
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_NAVIGATION' , 'NAVIGATION');

// define classes which reacts on event calls from within a template through
// the event handler class.
define ( 'EVT_NAVIGATION_GET' ,      'NAVIGATION_GET');


// register this handler                       
if (FALSE == $B->register_handler( MOD_NAVIGATION,
                           array ( 'module'        => MOD_NAVIGATION,
                                   'event_handler' => 'navigation_event_handler') ))
{
    trigger_error( 'The handler '.MOD_NAVIGATION_GET.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function navigation_event_handler( $evt )
{
    global $B;
    
    // check if this object was previously declared
    if(!is_object($B->$evt['code']))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . '/admin/modules/navigation/class.'.$evt['code'].'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $B->$evt['code'] = & new $evt['code']();
            // perform the request
            $B->$evt['code']->perform( $evt['data'] );
            return TRUE;  
        }
        else
        {
            return FALSE;
        } 
    }
    else
    {
        // perform the request if object exists
        $B->$evt['code']->perform( $evt['data'] );
        return TRUE; 
    }
}

?>
