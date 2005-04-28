<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * System module init
 *
 *
 */

// Check if this file is included in the SMART environement
//
if (!defined('SMART_SECURE_INCLUDE'))
{
    die('No Permission');
}

// Name of the module
define( 'SMART_MOD_SYSTEM' , 'system' );
// Version of the module
define( 'SMART_MOD_SYSTEM_VERSION' , SMART_VERSION );

// register this module                       
$this->model->register( SMART_MOD_SYSTEM,
                       array ( 'active'     => TRUE,
                               'visibility' => FALSE ) );

?>