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
define ( 'MAILARCHIVER_LISTS',          '1');
define ( 'MAILARCHIVER_LIST',           '2');
define ( 'MAILARCHIVER_LIST_MESSAGES',  '3');
define ( 'MAILARCHIVER_MESSAGE',        '4');
define ( 'MAILARCHIVER_MESSAGE_ATTACH', '5');
define ( 'MAILARCHIVER_ATTACH',         '6');

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
    //global $B;

    if(!is_object($mailarchiver))
    {
        // mailarchiver rights class
        include_once(SF_BASE_DIR.'/admin/modules/mailarchiver/class.public_mailarchiver.php');           
        $mailarchiver = & new mailarchiver;
    }

    switch( $evt["code"] )
    {            
        case MAILARCHIVER_LISTS: 
            return $mailarchiver->get_lists( $evt['data'] );
            break;  
        case MAILARCHIVER_LIST: 
            return $mailarchiver->get_list( $evt['data'] );
            break;   
        case MAILARCHIVER_LIST_MESSAGES: 
            return $mailarchiver->get_messages( $evt['data'] );
            break;
        case MAILARCHIVER_MESSAGE: 
            return $mailarchiver->get_message( $evt['data'] );
            break;     
        case MAILARCHIVER_MESSAGE_ATTACH: 
            return $mailarchiver->get_message_attach( $evt['data'] );
            break; 
        case MAILARCHIVER_ATTACH: 
            return $mailarchiver->get_attach( $evt['data'] );
            break;            
    }
}

?>
