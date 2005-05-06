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
 * Path to the config dir.
 * (Do not remove the original /config directory even if you set
 *  here an other config path)
 * If you change the folder path make shure that this directory 
 * is writeable by php.
 */
define('SMART_CONFIG_PATH',              SMART_BASE_DIR . 'config/');

/**
 * Idem for the directory of log files.
 */
 define('SMART_LOGS_PATH',               SMART_BASE_DIR . 'logs/');
    
/**
 * Idem for the cache folder.
 */
define('SMART_CACHE_PATH',               SMART_BASE_DIR . 'cache/');        

/**
 * Default public template and view folders
 */
define('SMART_DEFAULT_TEMPLATE_FOLDER',  'templates_default/');
define('SMART_DEFAULT_VIEW_FOLDER',      'views_default/');

/**
 * Default views.
 */
define('SMART_DEFAULT_VIEW',             'index');
define('SMART_ERROR_VIEW',               'error');
    
/**
 * message log types ('LOG|SHOW')
 */
define('SMART_MESSAGE_HANDLE',           'LOG');
  
/**
 * error reporting
 */
define('SMART_ERROR_REPORTING',          E_ALL | E_STRICT);

/**
 * Set debug mode.
 */
define('SMART_DEBUG',                    FALSE);    

/**
 * Name of the template engine class
 */
define('SMART_TEMPLATE_ENGINE',          'SmartTplContainerPhp');

?>