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
 * Default module init
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the module
define ( 'MOD_DEFAULT' , 'entry');

// Version of this module
define ( 'MOD_ENTRY_VERSION' , '0.3');

// register this module                      
if (FALSE == register_module( MOD_DEFAULT,
                               array ( 'module'          => MOD_DEFAULT,
                                       'menu_visibility' => TRUE) ))
{
    trigger_error( 'The module '.MOD_DEFAULT.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

?>