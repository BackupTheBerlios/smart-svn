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
define('SF_ERROR_HANDLE',             'LOG');

/**
 * error reporting E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR|E_PARSE
 */
define('SF_ERROR_REPORTING',          E_ALL ^E_NOTICE);

/**
 * Set debug.
 */
define('SF_DEBUG',                    FALSE);

/**
 * Allowed output compression if available.
 */
define('SF_OB_GZHANDLER',             FALSE); //'ob_gzhandler' or FALSE

/**
 * Default dir and file mode
 */
define('SF_DIR_MODE',                 0775);
define('SF_FILE_MODE',                0775);

/**
 * Default template group
 */
define('SF_DEFAULT_TEMPLATE_GROUP',   'default');


?>
