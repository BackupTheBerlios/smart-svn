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
 * Setup of the system
 * 
 */
// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// Check directories accesses
if(!is_writeable( SF_BASE_DIR . '/data' ))
{
    trigger_error("Must be writeable: " . SF_BASE_DIR . "/data\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/data';
    $success = FALSE;
}

if(!is_writeable( SF_BASE_DIR . '/admin/logs' ))
{
    trigger_error("Must be writeable: " . SF_BASE_DIR . "/admin/logs\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/logs';
    $success = FALSE;
}

if(!is_writeable( SF_BASE_DIR . '/admin/tmp' ))
{
    trigger_error("Must be writeable: " . SF_BASE_DIR . "/admin/tmp\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/tmp';
    $success = FALSE;
}

if(!is_writeable( SF_BASE_DIR . '/admin/config' ))
{
    trigger_error("Must be writeable: " . SF_BASE_DIR . "/admin/config\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/config';
    $success = FALSE;
}

if(!is_writeable( SF_BASE_DIR . '/admin/config/cache' ))
{
    trigger_error("Must be writeable: " . SF_BASE_DIR . "/admin/config/cache\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/config/cache';
    $success = FALSE;
}

// the version to install
include_once( SF_BASE_DIR . '/admin/include/system_version.php' );

// set name and version of the framework
$B->conf_val['info']['name']    = $B->system_name;
$B->conf_val['info']['version'] = $B->system_version;

?>