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
 * Basic Default Definitions
 *
 */

// Check if this file is included in SMART the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}


/**
 * error reporting
 */
define('SF_ERROR_REPORTING',             E_ALL);

/**
 * Enable Debug
 */
define('SF_DEBUG',                       TRUE);

/**
 * Show runtime messages like time execution and 
 * memory usage (only on php >= 4.3.2).
 * Unset this definition for a public release
 *
 */
define('SF_RUNTIME',                     TRUE);

/**
 * Allowed output buffering.
 */
define('SF_OB',                          TRUE);

/**
 * Allowed output compression if available.
 */
define('SF_OB_GZHANDLER',                "ob_gzhandler");


/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',                 'user');


/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',              'entry');


/**
 * Event types.
 */
define('SF_EVT_TYPE_BROADCAST',          1);
define('SF_EVT_TYPE_DIRECTED' ,          2); 


/**
 * Basic event codes.
 */
define('SF_EVT_AUTHENTICATE',            1);
define('SF_EVT_INIT',                    2);
define('SF_EVT_LOGOUT',                  3);
define('SF_EVT_LOAD_MODULE',             4);
define('SF_EVT_END',                     5);
define('SF_EVT_DEBUG',                   6);
define('SF_EVT_SETUP',                   7);


?>
