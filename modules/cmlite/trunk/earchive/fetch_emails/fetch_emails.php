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
 * fetch emails from email accounts.
 * This script should be executed by a cronjob
 */

/*
 * Secure include of files from this script
 */
define ('SF_SECURE_INCLUDE', 1);

// Define the absolute path to the Framework base
//
define ('SF_BASE_DIR', dirname(dirname(dirname(dirname(__FILE__)))) . '/');


// Include the base file
include( SF_BASE_DIR . 'index_inc_noview.php' );

// fetch emails
M( MOD_EARCHIVE, 'fetch_emails', array('status' => 'status>1') );

// Delete cache data
M( MOD_COMMON, 'cache_delete', array('group' => 'earchive'));

exit;


?>