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
define ( 'MOD_DEFAULT' , 'DEFAULT');

// Version of this modul
define ( 'MOD_ENTRY_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler( 
                           MOD_ENTRY,
                           array ( 'module'          => MOD_DEFAULT,
                                   'event_handler'   => 'default_event_handler',
                                   'menu_visibility' => TRUE) ))
{
    trigger_error( 'The handler '.MOD_DEFAULT.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function default_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            // set the base template for this module   
            $B->module = SF_BASE_DIR . '/admin/modules/default/templates/index.tpl.php';        
            break;             
        case EVT_INIT:        
            break; 
        case EVT_SETUP:
            $success = TRUE;
            // The module name and version
            $B->conf_val['module']['default']['name']     = 'default';
            $B->conf_val['module']['default']['version']  = MOD_DEFAULT_VERSION;
            $B->conf_val['module']['default']['mod_type'] = 'test';
            $B->conf_val['module']['default']['info']     = 'This is the default module';
        
            return $success;
            break;                  
    }
}

?>
