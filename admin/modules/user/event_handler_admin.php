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
 * Admin user module event handler
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'SF_EVT_HANDLER_USER' , 'user');

// register this handler                       
if (FALSE == $base->event->register_handler( 
                            SF_EVT_HANDLER_USER,
                            array ( 'module'        => SF_EVT_HANDLER_USER,
                                    'event_handler' => 'user_event_handler') ))
{
    trigger_error( 'The handler '.SF_EVT_HANDLER_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                        
                                        
                                        
// The handler function
function user_event_handler( $evt )
{
    global $base;

    switch( $evt["code"] )
    {
        case SF_EVT_AUTHENTICATE:
            // Include authentication script
            include(SF_BASE_DIR.'/admin/modules/user/auth.php');         
            break;
        case SF_EVT_LOAD_MODULE:
            // Include authentication script
            include(SF_BASE_DIR.'/admin/modules/user/module_loader.php');          
            break;             
        case SF_EVT_INIT:
            // Include authentication script
            include(SF_BASE_DIR.'/admin/modules/user/init.php');          
            break; 
        case SF_EVT_LOGOUT:  
            $base->user->logOut();
            break;  
        case SF_EVT_SETUP:       
            if( count($base->tmp_error) == 0 )
            {
                include(SF_BASE_DIR.'/admin/modules/user/_setup.php'); 
            }
            break;            
    }
}

?>
