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
 * Admin NAVIGATION module event handler
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_NAVIGATION' , 'NAVIGATION');

// Version of this modul
define ( 'MOD_NAVIGATION_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler( 
                            MOD_NAVIGATION,
                            array ( 'module'          => MOD_NAVIGATION,
                                    'event_handler'   => 'navigation_event_handler',
                                    'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_NAVIGATION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function navigation_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {           
        case EVT_INIT:
            // Check for upgrade  
            if(MOD_NAVIGATION_VERSION != (string)$B->sys['module']['navigation']['version'])
            {
                // set the new version num of this module
                $B->sys['module']['navigation']['version']  = MOD_NAVIGATION_VERSION;
                $B->system_update_flag = TRUE;  
                
                // include here additional upgrade code
            }
            break;                             
        case EVT_SETUP:  
            // init the success var
            $success = TRUE;
            
            // include here all stuff to get work this module:
            // creating db tables

            // The module name and version
            // these array vars were saved later by the setup handler
            // in the file /admin/config/config_system.xml.php
            //
            $B->conf_val['module']['navigation']['name']     = 'navigation';
            $B->conf_val['module']['navigation']['version']  = MOD_NAVIGATION_VERSION;
            $B->conf_val['module']['navigation']['mod_type'] = 'test';
            $B->conf_val['module']['navigation']['info']     = 'This is the navigation modul';
            
            // if noting is going wrong $success is still TRUE else FALSE
            // ex.: if creating db tables fails you must set this var to false
            return $success;
            break;            
    } 
}

?>
