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
    trigger_error( 'The handler '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
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

            //create session dir if it dosent exist
            if(!is_dir(SF_BASE_DIR . '/admin/tmp/session'))
            {
                if(!mkdir(SF_BASE_DIR . '/admin/tmp/session', SF_DIR_MODE))
                {
                    $B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . '/admin/tmp/session';
                    $success = FALSE;
                }  
            }
            if(($success == TRUE) && !is_writeable( SF_BASE_DIR . '/admin/tmp/session' ))
            {
                $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/tmp/session';
                $success = FALSE;
            }            
            
            // if noting is going wrong $success is still TRUE else FALSE
            // ex.: if creating db tables fails you must set this var to false
            return $success;        
            break;            
    } 
}

/***************************
**** Module SET  CONFIG ****
****************************/

// ### These 3 defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',                 'USER');

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',               'OPTION');

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',              'ENTRY');

/**
 * Media folder of this module set. (css, layout images, javascript)
 */
define('SF_MEDIA_FOLDER',                'modules/common/media');

/**
 * The main admin template. All subtemplates from other modules are included here
 */
define('SF_TEMPLATE_MAIN',               SF_BASE_DIR . '/admin/modules/common/templates/index.tpl.php');


// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages which is included in smartframe
ini_set( 'include_path', SF_BASE_DIR . '/admin/modules/common/PEAR' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator);

// include PEAR DB class
include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/DB.php');

// class instance of DB if setup is done
if($B->sys['info']['status'] == TRUE)
{ 
    $B->dsn = array('phptype'  => $B->sys['db']['dbtype'],
                    'username' => $B->sys['db']['user'],
                    'password' => $B->sys['db']['passwd'],
                    'hostspec' => $B->sys['db']['host'],
                    'database' => $B->sys['db']['name']);

    $B->dboptions = array('debug'       => 0,
                          'portability' => DB_PORTABILITY_NONE);
    
    $B->db =& DB::connect($B->dsn, $B->dboptions, TRUE);
    if (DB::isError($B->db)) 
    {
        trigger_error( 'Cannot connect to the database: '.__FILE__.' '.__LINE__, E_USER_ERROR  );
    }

    // include session class
    include_once( SF_BASE_DIR . '/admin/modules/common/class.sfSession.php' );  
    
    /* Create new object of session class */
    $B->session = & new session();
    
    // include util common class
    include_once( SF_BASE_DIR . '/admin/modules/common/class.commonUtil.php' );  
}

?>
