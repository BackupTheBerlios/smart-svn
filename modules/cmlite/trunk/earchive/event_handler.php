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
define ( 'MOD_EARCHIVE' , 'EARCHIVE');

// Version of this modul
define ( 'MOD_EARCHIVE_VERSION' , '0.3');

// define template event calls for this module
//
define ( 'EARCHIVE_LISTS',          'LISTS');          // all email lists (accounts)
define ( 'EARCHIVE_LIST',           'LIST');           // single email list (account) 
define ( 'EARCHIVE_LIST_MESSAGES',  'LIST_MESSAGES');  // all email list messages
define ( 'EARCHIVE_MESSAGE',        'MESSAGE');        // single email message
define ( 'EARCHIVE_MESSAGE_ATTACH', 'MESSAGE_ATTACH'); // all email attachements
define ( 'EARCHIVE_ATTACH',         'ATTACH');         // single email attachement
define ( 'EARCHIVE_SEARCH',         'SEARCH');         // searching for emails

// register this handler                       
if (FALSE == $B->register_handler( MOD_EARCHIVE,
                                   array ( 'module'           => MOD_EARCHIVE,
                                           'event_handler'    => 'earchive_event_handler',
                                           'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The handler '.MOD_EARCHIVE.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}   
                                                                          
// The handler function
function earchive_event_handler( $evt )
{
    global $B;

    // build the whole class name
    $class_name = 'EARCHIVE_'.$evt['code'];    
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . '/admin/modules/earchive/class.'.$class_name.'.php';
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
