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

/**
 * Public Front Controller
 *
 */
 
/*
 * Secure include of files from this script
 */
define('SF_SECURE_INCLUDE', 1);

// Define the absolute path to SMART
//
define('SF_BASE_DIR', dirname(__FILE__) . '/');

// Define section area
define('SF_SECTION', 'public');

// Include the base file
include( SF_BASE_DIR . 'smart/includes/core.inc.php' );

// Directed intercepting filter event (auto_prepend)
$B->M( MOD_SYSTEM, 'SYS_PREPEND' );

// Broadcast init event to all registered event handlers
$B->B( 'SYS_INIT' );

// Directed authentication event to the module handler, 
// which takes the authentication part
$B->M( SF_AUTH_MODULE, 'SYS_AUTHENTICATE' );

// get the public view (template)
include( $B->M( MOD_SYSTEM, 'GET_PUBLIC_VIEW') ); 

// Directed intercepting filter event (auto_append)
$B->M( MOD_SYSTEM, 'SYS_APPEND' );

// Send the output buffer to the client
ob_end_flush();

?>