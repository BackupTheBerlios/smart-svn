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
 * Admin TEST module event handler
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_USER' , 'user');

// Version of this module
define ( 'MOD_USER_VERSION' , '0.1');

// register this handler                       
if (FALSE == register_handler( MOD_USER,
                                   array ( 'module'           => MOD_USER,
                                           'event_handler'    => 'user_event_handler',
                                           'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The handler '.MOD_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}   
                                                                          
// The handler function
function user_event_handler( $evt )
{
    // build the whole class name
    $class_name = 'action_user_'.$evt['code'];    
    
    // check if this object was previously declared
    if(!is_object($GLOBALS[$class_name]))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . 'modules/user/actions/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $GLOBALS[$class_name] = & new $class_name();
            
            // validate the request
            if( FALSE == $GLOBALS[$class_name]->validate( $evt['data'] ) )
            {
                return FALSE;
            }
            // perform the request
            return $GLOBALS[$class_name]->perform( $evt['data'] );
        }
    }
    else
    {
        // validate the request
        if( FALSE == $GLOBALS[$class_name]->validate( $evt['data'] ) )
        {
            return FALSE;
        }  
        
        // perform the request if the requested object exists
        return $GLOBALS[$class_name]->perform( $evt['data'] );
    }
    return TRUE;
}

?>
