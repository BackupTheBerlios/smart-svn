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
define ( 'MOD_TEST' , 'TEST');

// register this handler                       
if (FALSE == $B->register_handler(MOD_TEST,
                                     array ( 'module'        => MOD_TEST,
                                             'event_handler' => 'test_event_handler') ))
{
    trigger_error( 'The handler '.MOD_TEST.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function test_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            // set the base template for this module
            $B->module = SF_BASE_DIR . '/admin/modules/test/templates/index.tpl.php';    
            // Assign module handler name
            $B->this_module = MOD_TEST;         
            break;             
        case EVT_INIT:    
            break; 
        case EVT_LOGOUT:  
            break;             
    }
}

?>
