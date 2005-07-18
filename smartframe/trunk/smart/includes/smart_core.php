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
 * Name of the admin web controller
 */
$SmartConfig['admin_web_controller'] = 'admin.php';

/**
 * Name of the public web controller
 */
$SmartConfig['public_web_controller'] = 'index.php';

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
 * Name of the cache type class
 */
$SmartConfig['cache_type'] = 'SmartFileViewCache';

/**
 * Name of the template engine class
 */
$SmartConfig['template_engine'] = 'SmartTplContainerPhp';

/**
 * Use php code analyzer
 */
$SmartConfig['useCodeAnalyzer'] = FALSE;

/**
 * Allowed php constructs in templates
 */
$SmartConfig['allowedConstructs'] = array('if','else','elseif','else if','endif',
                                          'foreach','endforeach','while','do','for',
                                          'continue','break','switch','case',
                                          'echo','print','print_r','var_dump','exit',
                                          'defined','define',
                                          'isset','empty');

/**
 * Module name from which retrive a view name.
 * The name of the class that is associated with a view
 */
$SmartConfig['view_request_module']  = 'navigation';
/**
 * The name of the request var from which the previous declared module
 * fetch the view name
 */
$SmartConfig['view_request_id_name'] = 'id_node';

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

/**
 * Rights for media folders and files
 */
$SmartConfig['media_folder_rights'] = 0777;
$SmartConfig['media_file_rights']   = 0777;

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
$SmartConfig['smart_version_name'] = 'SMART5';

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

// pass the config array to the controller
SmartController::setConfig( $SmartConfig );

?>