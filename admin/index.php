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
$B->M( SF_AUTH_MODULE, EVT_AUTHENTICATE );
 
// Send a init message to all registered handlers
$B->B(EVT_INIT);

// Logout
if ( $_REQUEST['logout'] == 1 )
{
    $B->B(EVT_LOGOUT);
    header ( 'Location: ../index.php' );
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

//  Output all templates
include(SF_BASE_DIR . '/admin/index.tpl.php');

// Send the output buffer to the client
if (SF_OB == TRUE)
{
    ob_end_flush ();
}
?>