<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/*
 * The Front Controller
 *
 * You should use this controller if you only want to use public
 * SMART views/templates in your projects. No admin view!
 *
 */


/*
 * Front Controller File Name (this file)
 */
if(!defined( 'SF_CONTROLLER' ))
{
   define('SF_CONTROLLER', 'index_inc_view.php'); 
}

/*
 * Relative path to SMART
 */
if(!defined( 'SF_RELATIVE_PATH' ))
{
   define('SF_RELATIVE_PATH', './'); 
}

/*
 * Force to use the views folder, independed of the sys config settings.
 */
/*
if(!defined( 'SF_VIEW_FOLDER' ))
{
   define('SF_VIEW_FOLDER', 'views_default/'); 
}
*/

/*
 * Force to use the templates folder, independed of the sys config settings.
 */
/*
if(!defined( 'SF_TPL_FOLDER' ))
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

// only access on public views and templates. No admin view
define('SF_SECTION', 'public');

// Broadcast init event to all registered module event handlers
// see modules/xxx/actions/class.xxx_sys_init.php
B( 'sys_init' );

// get the public view (template)
// see smart/actions/class.system_get_view.php
M( MOD_SYSTEM, 'get_view');

// Send the output buffer to the client
@ob_end_flush();

?>