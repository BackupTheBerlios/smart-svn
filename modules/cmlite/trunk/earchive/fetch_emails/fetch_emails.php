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
include( SF_BASE_DIR . 'smart/includes/core.inc.php' );

// Broadcast init event to all registered module event handlers
// see modules/xxx/actions/class.xxx_sys_init.php
$B->B( 'sys_init' );

// Include cache and create instance
if(!is_object($B->cache))
{
    include_once(SF_BASE_DIR . 'modules/common/PEAR/Cache.php');            
    $B->cache = new Cache('db', array('dsn'         => $B->dsn,
                                      'cache_table' => $B->sys['db']['table_prefix'].'cache'));
}
// Delete all cache data
$B->cache->flush('');

// fetch emails
$B->M( MOD_EARCHIVE, 'fetch_emails', array('status' => 'status>1') );

exit;


?>
