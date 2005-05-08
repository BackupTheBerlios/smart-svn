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
    if(SMART_DEBUG) die('No Permission on ' . __FILE__); else exit;
}

// Name of the module
define ( 'SMART_MOD_COMMON' , 'common');

// Version of this module
define ( 'SMART_MOD_COMMON_VERSION' , '0.1');

// register this module                       
$this->model->register( SMART_MOD_COMMON, array ( 'active'     => TRUE, 
                                                  'visibility' => FALSE ) );
// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages
ini_set( 'include_path', SMART_BASE_DIR . 'modules/common/includes/creole' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator); 

// creole db layer
require_once(SMART_BASE_DIR . 'modules/common/includes/creole/creole/Creole.php');

// util class
require_once(SMART_BASE_DIR . 'modules/common/includes/SmartCommonUtil.php');

// Default module
define ( 'SMART_DEFAULT_MODULE' ,      'default');

// Default module view
define ( 'SMART_DEFAULT_View' ,        'index');

// get_magic_quotes_gpc
define ( 'SMART_MAGIC_QUOTES' ,        get_magic_quotes_gpc());

?>