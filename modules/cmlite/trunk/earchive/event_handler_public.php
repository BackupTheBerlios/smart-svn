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
 * earchive module public event handler
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
define ( 'MOD_EARCHIVE' ,      'EARCHIVE');

// define template instruction calls for this module
// see details below in function 'earchive_event_handler'
//
define ( 'EARCHIVE_LISTS',          'EARCHIVE1');
define ( 'EARCHIVE_LIST',           'EARCHIVE2');
define ( 'EARCHIVE_LIST_MESSAGES',  'EARCHIVE3');
define ( 'EARCHIVE_MESSAGE',        'EARCHIVE4');
define ( 'EARCHIVE_MESSAGE_ATTACH', 'EARCHIVE5');
define ( 'EARCHIVE_ATTACH',         'EARCHIVE6');

// register this handler                       
if (FALSE == $B->register_handler(MOD_EARCHIVE,
                           array ( 'module'        => MOD_EARCHIVE,
                                   'event_handler' => 'earchive_event_handler') ))
{
    trigger_error( 'The handler '.MOD_EARCHIVE.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function earchive_event_handler( $evt )
{
    // check if a $earchive instance exists
    if(!is_object($earchive))
    {
        include_once(SF_BASE_DIR.'/admin/modules/earchive/class.public_earchive.php');           
        $earchive = & new earchive;
    }

    // Switch to the event code instruction
    switch( $evt["code"] )
    {            
        case EARCHIVE_LISTS: 
            // get all email lists
            return $earchive->get_lists( $evt['data'] );
            break;  
        case EARCHIVE_LIST: 
            // get a single email list
            return $earchive->get_list( $evt['data'] );
            break;   
        case EARCHIVE_LIST_MESSAGES: 
            // get email list messages
            return $earchive->get_messages( $evt['data'] );
            break;
        case EARCHIVE_MESSAGE: 
            // get a single message
            return $earchive->get_message( $evt['data'] );
            break;     
        case EARCHIVE_MESSAGE_ATTACH: 
            // get email message attachments
            return $earchive->get_message_attach( $evt['data'] );
            break; 
        case EARCHIVE_ATTACH: 
            // get a single attachment
            return $earchive->get_attach( $evt['data'] );
            break;            
    }
}

?>
