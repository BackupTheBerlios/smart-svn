<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
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
 * error reporting
 */
define('SF_ERROR_REPORTING',          E_ALL);

/**
 * Set debug mode.
 */
define('SF_DEBUG',                    TRUE);

/**
 * The common module name. This module is required!
 */
define('SF_COMMON_MODULE',            'common');

/**
 * Default templates and views folder
 */
define('SF_DEFAULT_TEMPLATE_FOLDER',   'templates_default/');
define('SF_DEFAULT_VIEW_FOLDER',       'views_default/');
/**
 * Template render flags
 */
define('SF_TEMPLATE_RENDER',          TRUE);
define('SF_TEMPLATE_RENDER_NONE',     FALSE);


?>