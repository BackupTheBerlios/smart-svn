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
 * Mailarchiver module public event handler
 * It handles instruction calls from templates
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

// define template instruction calls for this module
// see details below in function 'mailarchiver_event_handler'
//
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
    // check if a $mailarchiver instance exists
    if(!is_object($mailarchiver))
    {
        include_once(SF_BASE_DIR.'/admin/modules/mailarchiver/class.public_mailarchiver.php');           
        $mailarchiver = & new mailarchiver;
    }

    // Switch to the event code instruction
    switch( $evt["code"] )
    {            
        case MAILARCHIVER_LISTS: 
            // get all email lists
            return $mailarchiver->get_lists( $evt['data'] );
            break;  
        case MAILARCHIVER_LIST: 
            // get a single email list
            return $mailarchiver->get_list( $evt['data'] );
            break;   
        case MAILARCHIVER_LIST_MESSAGES: 
            // get email list messages
            return $mailarchiver->get_messages( $evt['data'] );
            break;
        case MAILARCHIVER_MESSAGE: 
            // get a single message
            return $mailarchiver->get_message( $evt['data'] );
            break;     
        case MAILARCHIVER_MESSAGE_ATTACH: 
            // get email message attachments
            return $mailarchiver->get_message_attach( $evt['data'] );
            break; 
        case MAILARCHIVER_ATTACH: 
            // get a single attachment
            return $mailarchiver->get_attach( $evt['data'] );
            break;            
    }
}

?>
