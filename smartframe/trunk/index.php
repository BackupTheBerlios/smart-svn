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
 * Relative path to SMART. Example: 'test/'
 */
if(!defined( SF_RELATIVE_PATH ))
{
   define('SF_RELATIVE_PATH', ''); 
}

/*
 * Force to use the template folder, independed of the sys config settings.
 */
/*
if(!defined( SF_VIEW_FOLDER ))
{
   define('SF_VIEW_FOLDER', 'views_default/'); 
}
*/

/*
 * Force to use the view folder, independed of the sys config settings.
 */
/*
if(!defined( SF_TPL_FOLDER ))
{
   define('SF_TPL_FOLDER', 'templates_default/'); 
}
*/

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

// Directed system event to execute the demanded view
// see: smart/actions/class.system_get_view.php
M( MOD_SYSTEM, 'get_view' );

// Send the output buffer to the client
while (@ob_end_flush());

?>