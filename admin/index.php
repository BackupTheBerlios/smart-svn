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
 * The main admin file
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

// send an authentication message to the handler which takes
// the authentication part
//$base->event->directed ( SF_AUTH_MODULE, SF_EVT_AUTHENTICATE );

// Send a init message to all registered handlers
$base->event->broadcast (SF_EVT_INIT);

// Logout
if ( $_REQUEST['logout'] == 1 )
{
    $base->event->broadcast (SF_EVT_LOGOUT);
    header ( 'Location: ../index.php' );
    exit;
}

// check if the demanded module (handler) is registered else load default module
if ( TRUE == $base->event->is_handler ($_REQUEST['m']))
{
    $base->event->directed ($_REQUEST['m'], SF_EVT_LOAD_MODULE);
}
else
{
    $base->event->directed (SF_DEFAULT_MODULE, SF_EVT_LOAD_MODULE);
}

//  Output all templates
$base->tpl->display ('/admin/index.tpl.php');

// Send the output buffer to the client
if (SF_OB == TRUE)
{
    ob_end_flush ();
}
?>