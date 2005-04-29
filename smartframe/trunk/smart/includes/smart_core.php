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

/*
 * The core include file
 *
 */

/*
 * Check if this script is included in the Smart environment
 */
if (!defined( 'SMART_SECURE_INCLUDE' ))
{
    die('no permission on ' . __FILE__);
}

// Start output buffering
//
@ob_start();

/**
 * Version info
 */
define('SMART_VERSION',                  '2.0a');
define('SMART_VERSION_NAME',             'SMART 5');

/**
 * message log types ('LOG|SHOW')
 */
define('SMART_MESSAGE_HANDLE',           'LOG|SHOW');

/**
 * error reporting
 */
define('SMART_ERROR_REPORTING',          E_ALL | E_STRICT);

/**
 * Set debug mode.
 */
define('SMART_DEBUG',                    TRUE);

/**
 * Path to the config file that contains sensitive data
 * like database conntection .....
 * If for security reasons you want to store such a file
 * outside the document root you have to define this
 * path here. Example:
 * /etc/smartconfig/
 * c:\myconfigurations\smart\
 * Such a directory must be readable/writeable by php scripts.
 * You have to set this path before running any setup proceedure 
 */
define('SMART_CONFIG_PATH',              SMART_BASE_DIR . 'config/');

/**
 * Idem for the directory of log files.
 */
define('SMART_LOGS_PATH',                SMART_BASE_DIR . 'logs/');

/**
 * The common module name. This module is required!
 */
define('SMART_COMMON_MODULE',            'common');

/**
 * Name of the default template class
 */
define('SMART_DEFAULT_TEMPLATE_ENGINE',  'SmartTplContainerPhp');

/**
 * Default templates and views folder
 */
define('SMART_DEFAULT_TEMPLATE_FOLDER',   'templates_default/');
define('SMART_DEFAULT_VIEW_FOLDER',       'views_default/');

define('SMART_DEFAULT_VIEW',              'index');
define('SMART_ERROR_VIEW',                'error');

/**
 * Template render flags
 */
define('SMART_TEMPLATE_RENDER',          TRUE);
define('SMART_TEMPLATE_RENDER_NONE',     FALSE);

/**
 * Error flags
 */
define('SMART_DIE',                      999); 
define('SMART_NO_MODULE',                1000);
define('SMART_NO_ACTION',                1001);
define('SMART_NO_VALID_ACTION',          1002);
define('SMART_NO_TEMPLATE',              1003);
define('SMART_NO_VIEW',                  1004);
define('SMART_ACTION_ERROR',             1005);


// smart object class
include_once( SMART_BASE_DIR . 'smart/includes/SmartObject.php' );

// smart exceptions
include_once( SMART_BASE_DIR . 'smart/includes/SmartException.php' );

// include smartErrorHandler
include_once( SMART_BASE_DIR . 'smart/includes/SmartErrorHandler.php' );

// The base container object
include_once( SMART_BASE_DIR . 'smart/includes/SmartContainer.php' );

// action class
include_once( SMART_BASE_DIR . 'smart/includes/SmartAction.php' );

// model class
include_once( SMART_BASE_DIR . 'smart/includes/SmartModel.php' );

// controller class
include_once( SMART_BASE_DIR . 'smart/includes/SmartController.php' );

?>