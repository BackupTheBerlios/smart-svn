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
 * Admin COMMON module event handler
 * This module does some init proccess and include 
 * external libraries needed by other modules
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_ENTRY' , 'ENTRY');

// register this handler                       
if (FALSE == $B->register_handler( MOD_ENTRY,
                                   array ( 'module'        => MOD_ENTRY,
                                           'event_handler' => 'entry_event_handler') ))
{
    trigger_error( 'The handler '.MOD_ENTRY.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}
                                                                          
// The handler function
function entry_event_handler( $evt )
{
    global $B;

    // build the whole class name
    $class_name = 'ENTRY_'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . '/admin/modules/entry/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $B->$class_name = & new $class_name();
            // perform the request
            return $B->$class_name->perform( $evt['data'] );
        }
        else
        {
            return FALSE;
        } 
    }
    else
    {
        // perform the request if the requested object exists
        return $B->$class_name->perform( $evt['data'] );
    }
}

?>
