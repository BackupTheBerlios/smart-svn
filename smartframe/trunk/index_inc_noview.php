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
 * The Front Controller, which provide no view
 *
 * You should use this controller if you only want to use
 * SMART events in your projects. This controller isnt providing
 * view/template capabilities
 *
 */

/*
 * Front Controller File Name (this file)
 */
if(!defined( SF_CONTROLLER ))
{
   define('SF_CONTROLLER', 'index_inc_noview.php'); 
}

/*
 * Relative path to SMART
 */
if(!defined( SF_RELATIVE_PATH ))
{
   define('SF_RELATIVE_PATH', ''); 
}

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

?>