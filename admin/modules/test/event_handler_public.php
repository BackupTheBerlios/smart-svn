<?php


/**
 * Test module event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'SF_EVT_HANDLER_TEST' , 'test');

// register this handler                       
if (FALSE == $base->event->register_handler( 
                           SF_EVT_HANDLER_TEST,
                           array ( 'module'        => SF_EVT_HANDLER_TEST,
                                   'event_handler' => 'test_event_handler') ))
{
    trigger_error( 'The handler '.SF_EVT_HANDLER_TEST.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function test_event_handler( $evt )
{
    global $base;

    switch( $evt["code"] )
    {          
        case SF_EVT_INIT: 
             break;           
    }
}

?>
