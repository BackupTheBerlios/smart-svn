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
 * The main admin file (admin front controller)
 *
 */
 
/*
 * Secure include of files from this script
 */
define ('SF_SECURE_INCLUDE', 1);

// Define the absolute path to the Framework base
//
define ('SF_BASE_DIR', dirname(dirname(__FILE__)));

// Define section area
define ('SF_SECTION', 'admin');

// Include the base file
include (SF_BASE_DIR . '/admin/include/base.inc.php');

// If setup flag is defined send a setup event to the handler which takes
// the setup part
if( defined( '_DO_SETUP' ) )
{
    if( FALSE == $B->M( MOD_SETUP, EVT_SETUP ) )
    {
        die("SETUP module is missing or not registered !!!");
    }
}  

// send an authentication message to the handler which takes
// the authentication part
$B->M( SF_AUTH_MODULE, EVT_AUTHENTICATE );

// Send a init message to all registered handlers
$B->B(EVT_INIT);

// if an update was done this event must be called to finish the update process
if(isset($B->system_update_flag))
{
    $B->M( SF_BASE_MODULE, EVT_UPDATE );
}

// Logout
if ( $_REQUEST['logout'] == 1 )
{
    $B->B(EVT_LOGOUT);
    header ( 'Location: '.SF_BASE_LOCATION.'/index.php' );
    exit;
}

// check if the demanded module (handler) is registered else load default module
if ( TRUE == $B->is_handler ($_REQUEST['m']))
{
    $B->M($_REQUEST['m'], EVT_LOAD_MODULE);
}
else
{
    $B->M(SF_DEFAULT_MODULE, EVT_LOAD_MODULE);
}

if(!defined( 'SF_TEMPLATE_MAIN' ) || !file_exists( SF_TEMPLATE_MAIN ))
{
    die('Missing main admin template!');
}

//  Output all templates
include( SF_TEMPLATE_MAIN );

// Send the output buffer to the client
if (SF_OB == TRUE)
{
    ob_end_flush ();
}
?>