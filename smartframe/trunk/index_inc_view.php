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
 * You should use this controller if you only want to use public
 * SMART views/templates in your projects. No admin view!
 *
 */


/*
 * Front Controller File Name
 */
if(!defined( SF_CONTROLLER ))
{
   define('SF_CONTROLLER', 'index_inc_view.php'); 
}

/*
 * Relative path to SMART
 */
if(!defined( SF_RELATIVE_PATH ))
{
   define('SF_RELATIVE_PATH', ''); 
}

/*
 * Force views folder, independed of the sys config settings.
 */
/*
if(!defined( SF_VIEW_FOLDER ))
{
   define('SF_VIEW_FOLDER', 'views_default/'); 
}
*/

/*
 * Force templates folder, independed of the sys config settings.
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

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Define the absolute path to SMART
//
define('SF_BASE_DIR', dirname(__FILE__) . '/');

// Include the base file
include( SF_BASE_DIR . 'smart/includes/core.inc.php' );

// Broadcast init event to all registered module event handlers
// see modules/xxx/actions/class.xxx_sys_init.php
B( 'sys_init' );

// if an update was done this event complete the update process
if(isset($B->system_update_flag))
{
    die('An update was done. You have to reload the SMART admin interface.');   
} 

// get the public view (template)
// see smart/actions/class.system_get_public_view.php
M( MOD_SYSTEM, 'get_view') );

// Send the output buffer to the client
@ob_end_flush();

?>