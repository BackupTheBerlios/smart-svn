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
 * error log types ('LOG|SHOW')
 */
define('SF_ERROR_HANDLE',               'SHOW|LOG');

/**
 * error reporting
 */
define('SF_ERROR_REPORTING',             E_ALL ^E_NOTICE);

/**
 * Enable Debug
 */
//define('SF_DEBUG',                       TRUE);

/**
 * Allowed output buffering.
 */
define('SF_OB',                          TRUE);

/**
 * Allowed output compression if available.
 */
define('SF_OB_GZHANDLER',                'ob_gzhandler'); //'ob_gzhandler' or FALSE

/**
 * Default dir and file mode
 */
define('SF_DIR_MODE',                    0775);
define('SF_FILE_MODE',                   0775);

/**
 * Event types.
 */
define('EVT_TYPE_BROADCAST',          1);
define('EVT_TYPE_DIRECTED' ,          2); 

/**
 * Basic event codes.
 */
define('EVT_AUTHENTICATE',            1);
define('EVT_INIT',                    2);
define('EVT_LOGOUT',                  3);
define('EVT_LOAD_MODULE',             4);
define('EVT_END',                     5);
define('EVT_DEBUG',                   6);
define('EVT_SETUP',                   7);
define('EVT_LOAD_INIT_OPTION',        8);
define('EVT_SETUP_FINISH',            9);
define('EVT_GET_OPTIONS',             10);


?>
