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
 * System event handler
 *
 *
 */

// Check if this file is included in the SMART environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

// Name of the event handler
define( 'MOD_SYSTEM' , 'SYSTEM' );

// register this handler                       
if (FALSE == $B->register_handler( MOD_SYSTEM,
                                   array ( 'module'        => MOD_SYSTEM,
                                           'event_handler' => 'system_event_handler') ))
{
    trigger_error( 'The handler '.MOD_SYSTEM.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function system_event_handler( $evt )
{
    global $B;

    // build the whole class name
    $class_name = 'SYSTEM_'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . 'smart/actions/class.'.$class_name.'.php';
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