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
 * Register of the module 'user'
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the module
define ( 'MOD_USER' , 'user');

// Version of this module
define ( 'MOD_USER_VERSION' , '0.2');

// register this module                       
if (FALSE == register_module( MOD_USER,
                              array ( 'module'           => MOD_USER,
                                      'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The module '.MOD_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}   
                                                                          
?>
