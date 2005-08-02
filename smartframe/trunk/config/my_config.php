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
 * My config file
 *
 * Here you can overwrite some configuration settings
 * for a production environment
 *
 * You have to rename this file to "my_config.php"
 *
 */

/*
 * Check if this script is included in the Smart environment
 * (Do not remove/change this definition)
 */
if (!defined( 'SMART_SECURE_INCLUDE' ))
{
    die('no permission on smart_core.php');
}

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
                                          'isset','empty','count');

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
$SmartConfig['default_template_folder'] = 'templates_smart/';
$SmartConfig['default_view_folder']     = 'views_smart/';

/**
 * Default views.
 */
$SmartConfig['default_view'] = 'index';
$SmartConfig['error_view']   = 'error';

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

?>