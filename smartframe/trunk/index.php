<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/*
 * The Front Controller
 *
 */

/*
 * Front Controller File Name (this file)
 */
if(!defined( SF_CONTROLLER ))
{
   define('SF_CONTROLLER', 'index.php'); 
}

/*
 * Relative path to SMART example: 'test/'
 */
if(!defined( SF_RELATIVE_PATH ))
{
   define('SF_RELATIVE_PATH', ''); 
}

/*
 * View folder.
 */
if(!defined( SF_VIEW_FOLDER ))
{
   define('SF_VIEW_FOLDER', 'views/'); 
}

/* #################################################### */
/* ######### Dont change any thing below !!! ########## */
/* #################################################### */

/* 
 * Secure include of files from this script
 */
if(!defined( SF_SECURE_INCLUDE ))
{
    define('SF_SECURE_INCLUDE', 1);
}

// Define the absolute path to SMART
//
define('SF_BASE_DIR', dirname(__FILE__) . '/');

// Include the system core file
include( SF_BASE_DIR . 'smart/includes/core.inc.php' );

// Define section area
if ($_REQUEST['admin'] == '1')
{
    define('SF_SECTION', 'admin');  
}
else
{
    define('SF_SECTION', 'public'); 
}

// Broadcast init event to all registered modules
// see modules/xxx/actions/class.xxx_sys_init.php
B( 'sys_init' );

// if an update was done this event complete the update process
if(isset($B->system_update_flag))
{
    // see modules/SF_BASE_MODULE/actions/class.SF_BASE_MODULE_sys_update_config.php
    M( SF_BASE_MODULE, 'sys_update_config', $B->sys );
    // reload page
    @header('Location: ' . SF_BASE_LOCATION . '/' . SF_CONTROLLER . '?' . SF_SECTION . '=1');
    exit;    
}  

// Directed system event to execute the demanded view
// see: smart/actions/class.system_get_view.php
M( MOD_SYSTEM, 'get_view' );

// Send the output buffer to the client
while (@ob_end_flush());

?>