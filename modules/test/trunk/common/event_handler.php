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
 * Admin COMMON module event handler
 * This module does some init proccess and include 
 * external libraries needed by other modules
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_COMMON' , 'common');

// Version of this modul
define ( 'MOD_COMMON_VERSION' , '0.3');

// register this handler                       
if (FALSE == register_handler( MOD_COMMON,
                               array ( 'module'          => MOD_COMMON,
                                       'event_handler'   => 'common_event_handler',
                                       'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function common_event_handler( $evt )
{
    // build the whole class name
    $class_name = 'action_common_'.$evt['code'];
        
    // check if this object was previously declared
    if(!is_object($GLOBALS[$class_name]))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . 'modules/common/actions/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $GLOBALS[$class_name] = & new $class_name();
            // perform the request
            return $GLOBALS[$class_name]->perform( $evt['data'] );
        }
    }
    else
    {
        // perform the request if the requested object exists
        return $GLOBALS[$class_name]->perform( $evt['data'] );
    }
    return TRUE;
}


/***************************
**** Module SET  CONFIG ****
****************************/

// ### These defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',      'user'); // required

/**
 * The module which play a base role required for all other modules.
 */
define('SF_BASE_MODULE',      MOD_COMMON); // required

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',    'option'); // required

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',   'default'); // required

/**
 * The main admin template. All subtemplates from other modules are included here // required
 */
define('SF_TEMPLATE_MAIN',     SF_BASE_DIR . 'modules/common/templates/index.tpl.php');

/**
 * Media folder of this module set. (css, layout images, javascript)
 */
define('SF_MEDIA_FOLDER',     'modules/common/media'); // optional

// common util class for all modules
include_once SF_BASE_DIR . 'modules/common/includes/class.commonUtil.php';

?>
