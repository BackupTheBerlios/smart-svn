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

// init smart configuration array
$SmartConfig = array();

/**
 * Path to the config dir
 */
$SmartConfig['config_path'] = SMART_BASE_DIR . 'config/';
/**
 * Idem for the directory of log files.
 */
$SmartConfig['logs_path'] = SMART_BASE_DIR . 'logs/';

/**
 * Cache folder.
 */
$SmartConfig['cache_path'] = SMART_BASE_DIR . 'cache/';
    
/**
 * The common module name. This module is required!
 */
$SmartConfig['base_module'] = 'common';

/**
 * The common module name. This module is required!
 */
$SmartConfig['setup_module'] = 'setup';
  
/**
 * Name of the template engine class
 */
$SmartConfig['template_engine'] = 'SmartTplContainerPhp';

/**
 * Default templates and views folder
 */
$SmartConfig['default_template_folder'] = 'templates_default';
$SmartConfig['default_view_folder'] = 'views_default';

/**
 * Default views.
 */
$SmartConfig['default_view'] = 'index';
$SmartConfig['error_view'] = 'error';

/**
 * message log types ('LOG|SHOW')
 */
$SmartConfig['message_handle'] = 'LOG|SHOW';
 
/**
 * error reporting
 */
$SmartConfig['error_reporting'] = E_ALL | E_STRICT;

/**
 * Set debug mode.
 */
$SmartConfig['debug'] = TRUE; 

// Check if there is a custom config file else load default config settings
//
if (@file_exists(SMART_BASE_DIR . 'config/my_config.php'))
{
    include_once(SMART_BASE_DIR . 'config/my_config.php');
}

/**
 * Version info
 */
$SmartConfig['smart_version'] = '2.0a';
$SmartConfig['smart_version_name'] = 'SMART 5';

/**
 * Error flags
 */
define('SMART_DIE',                      999); 
define('SMART_NO_MODULE',                1000);
define('SMART_NO_ACTION',                1001);
define('SMART_NO_TEMPLATE',              1003);
define('SMART_NO_VIEW',                  1004);

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