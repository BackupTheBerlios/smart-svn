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
define ('SF_BASE_DIR', dirname(dirname(__FILE__)) . '/');

// Define section area
define ('SF_SECTION', 'admin');

// Include the base file
include (SF_BASE_DIR . 'smart/includes/core.inc.php');

// Send a init message to all registered handlers
$B->B('SYS_INIT');

// if an update was done this event must be called to finish the update process
if(isset($B->system_update_flag))
{
    $B->M( SF_BASE_MODULE, 'SYS_FINSH_UPDATE' );
    // reload admin section
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
    exit;    
}

// send an authentication event to the handler which takes
// the authentication part
$B->M( SF_AUTH_MODULE, 'SYS_AUTHENTICATE' );

// Logout
if ( (int)$_REQUEST['logout'] == 1 )
{
    // each module can do clean ups before logout
    $B->B('SYS_LOGOUT');
    header ( 'Location: '.SF_BASE_LOCATION.'/index.php' );
    exit;
}

// Run the application logic of an admin module
//
// check if the demanded module (handler) is registered else load default module
if ( TRUE == $B->is_handler ($_REQUEST['m']))
{
    $B->M($_REQUEST['m'], 'SYS_LOAD_MODULE');
}
else
{
    $B->M(SF_DEFAULT_MODULE, 'SYS_LOAD_MODULE');
}

if(!defined( 'SF_TEMPLATE_MAIN' ) || !file_exists( SF_TEMPLATE_MAIN ))
{
    die('Missing main admin template!');
}

//  Output all templates
include( SF_TEMPLATE_MAIN );

ob_end_flush ();

?>