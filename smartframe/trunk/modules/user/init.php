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
 * USER module init
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SMART_SECURE_INCLUDE'))
{
    if(SMART_DEBUG == TRUE) die('No Permission on ' . __FILE__); else exit;
}

// register this module                       
$this->model->register( 'user', array ( 'active'     => TRUE, 
                                        'visibility' => TRUE ) );
// User role definitions
define ( 'SMART_MOD_USER_ROLE_ADMIN' ,     1);
define ( 'SMART_MOD_USER_ROLE_EDITOR' ,    20);
define ( 'SMART_MOD_USER_ROLE_AUTHOR' ,    40);
define ( 'SMART_MOD_USER_ROLE_CONTRIB_1' , 60);
define ( 'SMART_MOD_USER_ROLE_CONTRIB_2' , 80);

?>