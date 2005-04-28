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
 * Common module init
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SMART_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the module
define ( 'SMART_MOD_COMMON' , 'common');

// Version of this module
define ( 'SMART_MOD_COMMON_VERSION' , '0.1');

// register this module                       
$this->model->register( SMART_MOD_COMMON, array ( 'active'     => TRUE, 
                                                  'visibility' => FALSE ) );

?>