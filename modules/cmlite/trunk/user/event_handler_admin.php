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

// Version of this modul
define ( 'MOD_USER_VERSION' , '0.1.1');

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
            // User rights class
            include(SF_BASE_DIR.'/admin/modules/user/class.rights.php');          
        
            // Load a module feature
            include(SF_BASE_DIR.'/admin/modules/user/module_loader.php');          
            break;             
        case EVT_INIT:
            // Check for upgrade  
            if(MOD_USER_VERSION != (string)$B->sys['module']['user']['version'])
            {
                // set the new version num of this module
                $B->sys['module']['user']['version']  = MOD_USER_VERSION;
                $B->system_update_flag = TRUE;            
            }
            break;               
        case EVT_LOGOUT:  
            $B->session->destroy();
            @header('Location: '.SF_BASE_LOCATION.'/index.php');
            exit;
            break; 
        case EVT_SET_OPTIONS:  
            // set user options 
            // this event comes from the option module (module_loader.php)
            if(isset($_POST['update_user_options_allowreg']))
            {
                $B->sys['option']['user']['allow_register'] = (bool)$_POST['userallowregister'];
                $B->sys['option']['user']['register_type']  = $_POST['userregistertype'];
            }
            break;             
        case EVT_GET_OPTIONS:  
            // get user options template 
            // to include in the option module
            $B->mod_option[] = SF_BASE_DIR.'/admin/modules/user/templates/option.tpl.php';
            break;                
        case EVT_SETUP:  
            $success = TRUE;
            include(SF_BASE_DIR.'/admin/modules/user/_setup.php'); 
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

// class instance of DB if setup is done
if($B->sys['info']['status'] == TRUE)
{
    // include PEAR DB class
    include_once( SF_BASE_DIR . '/admin/lib/PEAR/DB.php');
        
    // if sqlite set host to the db file
    if($B->sys['db']['dbtype'] == 'sqlite')
    {
        $B->sys['db']['name'] = SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php';
    }
    
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
}
?>
