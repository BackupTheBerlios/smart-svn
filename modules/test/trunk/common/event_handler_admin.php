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
define ( 'MOD_COMMON' , 'COMMON');

// Version of this modul
define ( 'MOD_COMMON_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler( 
                            MOD_COMMON,
                            array ( 'module'          => MOD_COMMON,
                                    'event_handler'   => 'common_event_handler',
                                    'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_TEST_ERROR  );        
}    
                                                                          
// The handler function
function common_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {            
        case EVT_INIT:
            // Check for upgrade  
            if(MOD_COMMON_VERSION != (string)$B->sys['module']['common']['version'])
            {
                // set the new version num of this module
                $B->sys['module']['common']['version']  = MOD_COMMON_VERSION;
                $B->system_update_flag = TRUE;  
                
                // include here additional upgrade code
            }
            break;               
        case EVT_LOGOUT:  
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
            $B->conf_val['module']['common']['name']     = 'common';
            $B->conf_val['module']['common']['version']  = MOD_COMMON_VERSION;
            $B->conf_val['module']['common']['mod_type'] = 'common';
            $B->conf_val['module']['common']['info']     = 'This is the common modul';
            
            // if noting is going wrong $success is still TRUE else FALSE
            // ex.: if creating db tables fails you must set this var to false
            return $success;        
            break;            
    } 
}

/* 
Include and define here data, which are required by other modules.
This means that you must define at least 3 variables: 
- SF_AUTH_MODULE
- SF_OPTION_MODULE
- SF_DEFAULT_MODULE
For detailed info of this vars see below.

Furthermore if you modules require a database connection or some class instances
or packages like PEAR, ADODB, ... you have to include those here.

*/

/***************************
**** Module SET  CONFIG ****
****************************/

// ### These 3 defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',                 'TEST');

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',               'OPTION');

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',              'ENTRY');

?>
