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
define ( 'MOD_MAILARCHIVER' ,      'MAILARCHIVER');

// define template functions of this modul
define ( 'MAILARCHIVER_GET_LISTS' , '1');

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
        case MAILARCHIVER_GET_LISTS: 
            if(!is_object($B->marchiver))
            {
                // mailarchiver rights class
                include_once(SF_BASE_DIR.'/admin/modules/mailarchiver/class.public_mailarchiver.php');           
                $B->marchiver = & new mailarchiver;
            }
            return $B->marchiver->get_lists( $evt['data'] );
            break;                
    }
}

?>
