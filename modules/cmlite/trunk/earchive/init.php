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

// Name of the event handler
define ( 'MOD_EARCHIVE' , 'earchive');

// Version of this modul
define ( 'MOD_EARCHIVE_VERSION' , '0.4.4');

// register this module                       
if (FALSE == register_module( MOD_EARCHIVE,
                              array ( 'module'           => MOD_EARCHIVE,
                                      'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The module '.MOD_EARCHIVE.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}   
                                                                          
?>
