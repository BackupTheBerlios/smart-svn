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
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/data';
}

if(!is_writeable( SF_BASE_DIR . '/logs' ))
{
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/logs';
}

// the version to install
include_once( SF_BASE_DIR . '/admin/include/system_version.php' );

// Do setup if no error
if( $_POST['do_setup'] && (count($B->setup_error) == 0) )
{
    // set name and version of the framework
    $B->conf_val['info']['name']    = $B->system_name;
    $B->conf_val['info']['version'] = $B->system_version;
    // set default media folder of the framework
    $B->conf_val['option']['css_folder'] = 'default';
}

?>