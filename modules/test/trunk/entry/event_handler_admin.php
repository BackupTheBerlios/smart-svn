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

// Version of this modul
define ( 'MOD_ENTRY_VERSION' , '0.1');

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
            // set the base template for this module   
            $B->module = SF_BASE_DIR . '/admin/modules/entry/templates/index.tpl.php';        
            break;             
        case EVT_INIT:        
            break; 
        case EVT_SETUP:
            $success = TRUE;
            // The module name and version
            $B->conf_val['module']['entry']['name']     = 'entry';
            $B->conf_val['module']['entry']['version']  = MOD_ENTRY_VERSION;
            $B->conf_val['module']['entry']['mod_type'] = 'test';
            $B->conf_val['module']['entry']['info']     = 'This is the entry module';
        
            return $success;
            break;                  
    }
}

?>
