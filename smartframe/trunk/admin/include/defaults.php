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
    die('No Permission on '. __FILE__);
}


/**
 * error log types ('LOG|SHOW')
 */
define('SF_ERROR_HANDLE',             'LOG|SHOW');

/**
 * error reporting
 */
define('SF_ERROR_REPORTING',          E_ALL ^E_NOTICE);

/**
 * Allowed output buffering.
 */
define('SF_OB',                       TRUE);

/**
 * Allowed output compression if available.
 */
define('SF_OB_GZHANDLER',             'ob_gzhandler'); //'ob_gzhandler' or FALSE

/**
 * Default dir and file mode
 */
define('SF_DIR_MODE',                 0775);
define('SF_FILE_MODE',                0775);

/**
 * Default template
 */
define('SF_DEFAULT_TEMPLATE_GROUP',   'default');

/**
 * Event types.
 */
define('EVT_TYPE_BROADCAST',          'SYS_BROADCAST');
define('EVT_TYPE_DIRECTED' ,          'SYS_DIRECTED'); 

/**
 * Basic event codes.
 */
define('EVT_AUTHENTICATE',            'SYS_AUTHENTICATE');
define('EVT_INIT',                    'SYS_INIT');
define('EVT_LOGOUT',                  'SYS_LOGOUT');
define('EVT_LOAD_MODULE',             'SYS_LOAD_MODULE');
define('EVT_END',                     'SYS_END');
define('EVT_DEBUG',                   'SYS_DEBUG');
define('EVT_SETUP',                   'SYS_SETUP');
define('EVT_LOAD_INIT_OPTION',        'SYS_LOAD_INIT_OPTION');
define('EVT_SETUP_FINISH',            'SYS_SETUP_FINISH');
define('EVT_GET_OPTIONS',             'SYS_GET_OPTIONS');
define('EVT_UPDATE',                  'SYS_UPDATE');


?>
