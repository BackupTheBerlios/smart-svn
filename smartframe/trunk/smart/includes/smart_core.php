<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
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
@ob_end_clean();
ob_start();

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
 * The default module name. This module is required!
 */
$SmartConfig['default_module'] = 'default';

/**
 * The setup module name. This module is required!
 */
$SmartConfig['setup_module'] = 'setup';
  
/**
 * Name of the cache type class
 */
$SmartConfig['cache_type'] = 'SmartFileViewCache';

/**
 * cache time type       // filemtime or filestime
 */
$SmartConfig['cache_time_type'] = 'filemtime';

/**
 * Name of the template engine class used for the public templates
 */
$SmartConfig['public_template_engine'] = 'SmartTplContainerPhp';

/**
 * Name of the template engine class used for the module templates
 */
$SmartConfig['template_engine'] = 'SmartTplContainerPhp';

/**
 * Use php code analyzer
 * This an experimental feature.
 */
$SmartConfig['useCodeAnalyzer'] = FALSE;

/**
 * Allowed php constructs in templates
 */
$SmartConfig['allowedConstructs'] = array('if','else','elseif','else if','endif',
                                          'foreach','endforeach','while','do','for','endfor',
                                          'continue','break','switch','case',
                                          'echo','print','print_r','var_dump','exit',
                                          'defined','define',
                                          'isset','empty','count');

/**
 * admin view folder
 */
$SmartConfig['admin_view_folder'] = 'views/';                                          
                                          
/**
 * Default template and view folders
 */
$SmartConfig['default_template_folder'] = 'templates_smart/';
$SmartConfig['default_view_folder']     = 'views_smart/';

/**
 * Default views.
 */
$SmartConfig['default_view']     = 'index';
$SmartConfig['error_view']       = 'error';

/**
 * enable output compression (FALSE = disable, TRUE = enable)
 */
$SmartConfig['output_compression']       = FALSE;

/**
 * output compression level 1-9
 */
$SmartConfig['output_compression_level'] = '4';

/**
 * recipient email of system messages: system@foo.com
 */
$SmartConfig['system_email'] = '';

/**
 * message log types ('LOG|SHOW|MAIL')
 */
$SmartConfig['message_handle'] = 'LOG';
 
/**
 * error reporting
 */
$SmartConfig['error_reporting'] = E_ALL;

/**
 * Set debug mode.
 */
$SmartConfig['debug'] = FALSE; 

/**
 * Rights for media folders and files
 */
$SmartConfig['media_folder_rights'] = 0777;
$SmartConfig['media_file_rights']   = 0777;

// Check if there is a custom config file else use default config settings
//
if (@file_exists(SMART_BASE_DIR . 'config/my_config.php'))
{
    include_once(SMART_BASE_DIR . 'config/my_config.php');
}

/**
 * Module name from which retrive a view name.
 * The name of the class that is associated with a view
 */
$SmartConfig['view_map']  = array();

/**
 * Version info
 */
$SmartConfig['smart_version'] = '0.2.1a';
$SmartConfig['smart_version_name'] = 'SMART3';

/**
 * Disable cache global
 */
$SmartConfig['disable_cache'] = 0;

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to PEAR base
ini_set( 'include_path', SMART_BASE_DIR . 'smart/includes/PEAR' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator); 

#so smart object
include_once( SMART_BASE_DIR . 'smart/includes/SmartObject.php' );
#so

#se smart exceptions
include_once( SMART_BASE_DIR . 'smart/includes/SmartException.php' );
#se

#eh error handler
include_once( SMART_BASE_DIR . 'smart/includes/SmartErrorHandler.php' );
#eh

#co container object
include_once( SMART_BASE_DIR . 'smart/includes/SmartContainer.php' );
#co

#ac action class
include_once( SMART_BASE_DIR . 'smart/includes/SmartAction.php' );
#ac

#mc model class
include_once( SMART_BASE_DIR . 'smart/includes/SmartModel.php' );
#mc

#cc controller class
include_once( SMART_BASE_DIR . 'smart/includes/SmartController.php' );
#cc

#scc cache class
include_once( SMART_BASE_DIR . 'smart/includes/SmartCache.php' );
#scc

// pass the config array to the controller
SmartController::setConfig( $SmartConfig );

?>