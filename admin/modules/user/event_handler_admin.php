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
 *
 * This is also the main module of this set of modules
 * This means that here you must include all stuff (classes functions defines) 
 * to get work the module set. (See below)
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_USER' , 'USER');

// register this handler                       
if (FALSE == $B->register_handler( 
                            MOD_USER,
                            array ( 'module'        => MOD_USER,
                                    'event_handler' => 'user_event_handler') ))
{
    trigger_error( 'The handler '.MOD_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function user_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_AUTHENTICATE:
            $B->login = TRUE;
            include_once(SF_BASE_DIR.'/admin/modules/user/class.auth.php');
            $B->auth = & new auth('admin');  
            if($B->auth->is_user == FALSE)
                include(SF_BASE_DIR.'/admin/modules/user/login.php');
            break;
        case EVT_LOAD_MODULE:
            // Load a module feature
            include(SF_BASE_DIR.'/admin/modules/user/module_loader.php');          
            break;             
        case EVT_INIT:
            // System Name and Version
            $B->tmp_module_name    = 'User';
            $B->tmp_module_version = '0.1';
        
            // Check for upgrade  
            if(version_compare($B->tmp_module_version, $B->sys['module']['user']['version']) != 0 )
                include(SF_BASE_DIR.'/admin/modules/user/upgrade.php');
                        
            // Name of this module set
            $B->tpl_mod_set_name = 'CM-Lite';
            break;               
        case EVT_LOGOUT:  
            $B->session->destroy();
            @header('Location: index.php');
            exit;
            break;  
        case EVT_SETUP:       
            if( count($base->tmp_error) == 0 )
            {
                include(SF_BASE_DIR.'/admin/modules/user/_setup.php'); 
            }
            break;            
    } 
}

/**********************************
**** Module SET CM-LITE CONFIG ****
**********************************/

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


// include sqlite class
include_once( SF_BASE_DIR . '/admin/modules/user/class.Sqlite.php' );

// User rights class
include(SF_BASE_DIR.'/admin/modules/user/class.rights.php');          

// class instance of sqlite if setup is done
if($B->sys['info']['status'] == TRUE)
{
    // Connect to the main database
    $B->dbdata = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php');
    $B->dbdata->turboMode();
}
?>
