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
define ( 'MOD_COMMON_VERSION' , '0.2');

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
            
            // Assign registered module handlers template var
            $B->tpl_mod_list = array();
            
            // sort handler list by name
            ksort($B->handler_list);
            // assign template handler list array
            foreach ($B->handler_list as $key => $value)
            {
                if( $value['menu_visibility'] == TRUE )
                {
                    $B->tpl_mod_list[$key] =  $value;
                }
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
This means that you must define at least these variables: 
- SF_AUTH_MODULE
- SF_OPTION_MODULE
- SF_DEFAULT_MODULE
- SF_TEMPLATE_MAIN
For detailed info of this vars see below.

Furthermore if you modules require a database connection or some class instances
or packages like PEAR, ADODB, ... you have to include those here.

*/

/***************************
**** Module SET  CONFIG ****
****************************/

// ### These defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',      'TEST'); // required

/**
 * The module which play a base role required for all other modules.
 */
define('SF_BASE_MODULE',                 MOD_COMMON);

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',    'OPTION'); // required

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',   'DEFAULT'); // required

/**
 * The main admin template. All subtemplates from other modules are included here // required
 */
define('SF_TEMPLATE_MAIN',     SF_BASE_DIR . '/admin/modules/common/templates/index.tpl.php');

/**
 * Media folder of this module set. (css, layout images, javascript)
 */
define('SF_MEDIA_FOLDER',     'modules/common/media'); // optional

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages
ini_set( 'include_path', SF_BASE_DIR . '/admin/modules/common/PEAR' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator); 

// init system config array
$B->sys = array();

// include system config array
if(file_exists(SF_BASE_DIR . '/admin/modules/common/config/config.php'))
    include_once( SF_BASE_DIR . '/admin/modules/common/config/config.php' );  

// if setup was done
if($B->sys['info']['status'] == TRUE)
{ 
    // here you may create db connection and start a session.
    // .... things, which are required by all other modules
    
}
// Set setup flag if setup wasnt done
else
{
    define('_DO_SETUP', TRUE);
}

?>
