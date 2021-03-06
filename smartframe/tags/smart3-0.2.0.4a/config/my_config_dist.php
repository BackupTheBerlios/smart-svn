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
 * (Do not remove/change this check)
 */
if (!defined( 'SMART_SECURE_INCLUDE' ))
{
    exit;
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
 * Path to the config dir
 */
$SmartConfig['config_path'] = SMART_BASE_DIR . 'config/';

/**
 * Path to the logs dir
 */
$SmartConfig['logs_path'] = SMART_BASE_DIR . 'logs/';

/**
 * Path to the cache dir
 */
$SmartConfig['cache_path'] = SMART_BASE_DIR . 'cache/';

/**
 * Name of the cache type class
 */
$SmartConfig['cache_type'] = 'SmartFileViewCache';

/**
 * cache time type       // filemtime or filestime
 */
$SmartConfig['cache_time_type'] = 'filemtime';
 
/**
 * Name of the template engine class
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
                                          'defined','define','isset','empty','count');
                                          
/**
 * Default templates and views folder
 */
$SmartConfig['default_template_folder'] = 'templates_smart/';
$SmartConfig['default_view_folder']     = 'views_smart/';

/**
 * Default index and error views.
 */
$SmartConfig['default_view'] = 'index';
$SmartConfig['error_view']   = 'error';

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
$SmartConfig['error_reporting'] = E_ALL | E_STRICT;

/**
 * Set debug mode.
 */
$SmartConfig['debug'] = FALSE; 

?>