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
 * Admin option module init
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the module
define ( 'MOD_OPTION' , 'option');

// Version of this modul
define ( 'MOD_OPTION_VERSION' , '0.1.6');

// register this module                       
if (FALSE == register_module( MOD_OPTION,
                              array ( 'module'           => MOD_OPTION,
                                      'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The module '.MOD_OPTION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

?>