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
 * Entry module event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_ENTRY' , 'ENTRY');

// register this handler                       
if (FALSE == $B->register_handler( 
                           MOD_ENTRY,
                           array ( 'module'        => MOD_ENTRY,
                                   'event_handler' => 'entry_event_handler') ))
{
    trigger_error( 'The handler '.MOD_ENTRY.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function entry_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            include(SF_BASE_DIR.'/admin/modules/entry/module_loader.php');          
            break;             
        case EVT_INIT:        
            break; 
        case EVT_LOGOUT:  
            break;             
    }
}

?>
