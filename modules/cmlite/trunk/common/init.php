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
define ( 'MOD_COMMON' , 'user');

// Version of this module
define ( 'MOD_COMMON_VERSION' , '0.4');

// register this module                       
if (FALSE == register_module( MOD_COMMON,
                              array ( 'module'           => MOD_COMMON,
                                      'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The module '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}   
 
/***************************
**** Module SET  CONFIG ****
****************************/

// ### These defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',      'user'); // required

/**
 * The module which play a base role required for all other modules.
 */
define('SF_BASE_MODULE',      MOD_COMMON); // required

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',    'option'); // required

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',   'entry'); // required

/**
 * The main admin template. All module views are included in this template // required
 */
define('SF_TEMPLATE_MAIN',     SF_BASE_DIR . 'modules/common/templates/index.tpl.php');

/**
 * Media folder of this module set. (css, layout images, javascript)
 */
define('SF_MEDIA_FOLDER',     'modules/common/media'); // optional

/**
 * Get get_magic_quotes_gpc
 */
define('SF_MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

// static common util class for all modules
include_once SF_BASE_DIR . 'modules/common/includes/class.commonUtil.php'; 
 
?>
