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
 * Public user module event handler
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'SF_EVT_HANDLER_USER' , 'user');

// register this handler                       
$base->event->register_handler( SF_EVT_HANDLER_USER,
                                array ( "module"        => SF_EVT_HANDLER_USER,
                                        "event_handler" => "user_event_handler") );
// The handler function
function user_event_handler( $evt )
{
    global $base;

    switch( $evt["code"] )
    {
        case SF_EVT_AUTHENTICATE:
            break;
        case SF_EVT_INIT:        
            break;           
    }
}

?>
