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
 * Admin setup module event handler
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_SETUP' , 'setup');

// Version of this module
define ( 'MOD_SETUP_VERSION' , '0.2');

// register this handler                       
if (FALSE == $B->register_handler( MOD_SETUP,
                                   array ( 'module'          => MOD_SETUP,
                                           'event_handler'   => 'setup_event_handler',
                                           'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_SETUP.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                        
                                        
                                        
// The setup handler function
function setup_event_handler( $evt )
{
    global $B;

    // build the whole class name
    $class_name = 'setup_'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . 'modules/setup/actions/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $B->$class_name = & new $class_name();
            // perform the request
            return $B->$class_name->perform( $evt['data'] );
        }    
        return FALSE;
    }
    else
    {
        // perform the request if the requested object exists
        return $B->$class_name->perform( $evt['data'] );
    }
}

?>
