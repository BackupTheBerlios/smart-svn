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
 * Admin Front Controller
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

// send an authentication event to the handler which takes
// the authentication part
$B->M( SF_AUTH_MODULE, 'sys_authenticate' );

// Send a init message to all registered handlers
$B->B('sys_init');

// if an update was done this event finish the update process
if(isset($B->system_update_flag))
{
    $B->M( SF_BASE_MODULE, 'sys_finish_update' );
    // reload admin section
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
    exit;    
}

// Logout
if ( (int)$_REQUEST['logout'] == 1 )
{
    // each module can do clean ups before logout
    $B->B('sys_logout');
    header ( 'Location: '.SF_BASE_LOCATION.'/index.php' );
    exit;
}

// get the admin view
include( $B->M( MOD_SYSTEM, 'get_admin_view') ); 

ob_end_flush ();

?>