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
define ( 'MOD_MAILARCHIVER' , 'MAILARCHIVER');

// register this handler                       
if (FALSE == $B->register_handler(MOD_MAILARCHIVER,
                                     array ( 'module'        => MOD_MAILARCHIVER,
                                             'event_handler' => 'mailarchiver_event_handler') ))
{
    trigger_error( 'The handler '.MOD_MAILARCHIVER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function mailarchiver_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            // set the base template for this module
            include(SF_BASE_DIR . '/admin/modules/mailarchiver/module_loader.php');           
            break;             
        case EVT_INIT:   
            break; 
        case EVT_LOGOUT:  
            break;  
        case EVT_SETUP:       
            if( count($base->tmp_error) == 0 )
            {
                include(SF_BASE_DIR.'/admin/modules/mailarchiver/_setup.php'); 
            }
            break;            
    }
}

?>
