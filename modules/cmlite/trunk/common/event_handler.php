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
 * common module event handler
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

// Version of this module
define ( 'MOD_COMMON_VERSION' , '0.4');

// register this handler                       
if (FALSE == $B->register_handler( MOD_COMMON,
                                   array ( 'module'          => MOD_COMMON,
                                           'event_handler'   => 'common_event_handler',
                                           'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function common_event_handler( $evt )
{
    global $B;
    
    // build the whole class name
    $class_name = 'common_'.$evt['code'];
        
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . 'modules/common/actions/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $B->$class_name = & new $class_name();
            // perform the request
            return $B->$class_name->perform( $evt['data'] );
        }
    }
    else
    {
        // perform the request if the requested object exists
        return $B->$class_name->perform( $evt['data'] );
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
define('SF_AUTH_MODULE',                 'user');

/**
 * The module which play a base role required for all other modules.
 */
define('SF_BASE_MODULE',                 MOD_COMMON);

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',               'option');

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',              'entry');

/**
 * Media folder of this module set. (css, layout images, javascript)
 */
define('SF_MEDIA_FOLDER',                'modules/common/media');

// common util class for all modules
include_once SF_BASE_DIR . 'modules/common/includes/class.commonUtil.php';

?>
