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
 * Admin setup module init
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the module
define ( 'MOD_SETUP' , 'setup');

// Version of this module
define ( 'MOD_SETUP_VERSION' , '0.2');

// register this module                       
if (FALSE == register_module( MOD_SETUP,
                              array ( 'module'           => MOD_SETUP,
                                      'menu_visibility'  => FALSE) ))
{
    trigger_error( 'The module '.MOD_SETUP.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

?>