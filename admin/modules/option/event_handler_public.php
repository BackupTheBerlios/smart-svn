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
 * Option module public event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_OPTION' , 'OPTION');

// register this handler                       
if (FALSE == $B->register_handler(MOD_OPTION,
                              array ( 'module'        => MOD_OPTION,
                                      'event_handler' => 'option_event_handler') ))
{
    trigger_error( 'The handler '.MOD_OPTION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function option_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case SF_EVT_LOAD_INIT_OPTION:
            break;                  
        case SF_EVT_INIT:    
            break; 
        case SF_EVT_LOGOUT:  
            break;             
    }
}
?>
