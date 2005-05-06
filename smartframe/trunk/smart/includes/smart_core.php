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
    die('no permission on smart_core.php');
}

// Start output buffering
//
@ob_start();

// Check if there is a custom config file else load default config settings
//
if (@file_exists(SMART_BASE_DIR . 'config/my_config.php'))
{
    include_once(SMART_BASE_DIR . 'config/my_config.php');
}
else
{
    /**
     * Path to the config dir
     */
    define('SMART_CONFIG_PATH',              SMART_BASE_DIR . 'config/');

    /**
     * Idem for the directory of log files.
     */
    define('SMART_LOGS_PATH',                SMART_BASE_DIR . 'logs/');
    
    /**
     * Cache folder.
     */
    define('SMART_CACHE_PATH',                SMART_BASE_DIR . 'cache/');        
    
    /**
     * The common module name. This module is required!
     */
    define('SMART_COMMON_MODULE',            'common');

    /**
     * Name of the template engine class
     */
    define('SMART_TEMPLATE_ENGINE',          'SmartTplContainerPhp');

    /**
     * Default templates and views folder
     */
    define('SMART_DEFAULT_TEMPLATE_FOLDER',   'templates_default/');
    define('SMART_DEFAULT_VIEW_FOLDER',       'views_default/');

    /**
     * Default views.
     */
    define('SMART_DEFAULT_VIEW',              'index');
    define('SMART_ERROR_VIEW',                'error');
    
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
}

/**
 * Version info
 */
define('SMART_VERSION',                  '2.0a');
define('SMART_VERSION_NAME',             'SMART 5');

/**
 * Template render flags
 */
define('SMART_TEMPLATE_RENDER',          TRUE);
define('SMART_TEMPLATE_RENDER_NONE',     FALSE);

/**
 * The common module name. This module is required!
 */
define('SMART_COMMON_MODULE',            'common');

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
define('SMART_VIEW_ERROR',               1006);
define('SMART_TEMPLATE_ERROR',           1006);

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

// cache class
include_once( SMART_BASE_DIR . 'smart/includes/SmartCache.php' );

?>