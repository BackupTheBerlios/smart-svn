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
 * Public common module event handler
 * This module does some init proccess and include 
 * external libraries needed by other modules
 * 
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_COMMON' , 'COMMON');


// register this handler                       
if (FALSE == $B->register_handler(MOD_COMMON,
                           array ( 'module'        => MOD_COMMON,
                                   'event_handler' => 'common_event_handler') ))
{
    trigger_error( 'The handler '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function common_event_handler( $evt )
{
    global $B;

   // build the whole class name
    $class_name = 'COMMON_'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . '/admin/modules/common/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $B->$class_name = & new $class_name();
            // perform the request
            return $B->$class_name->perform( $evt['data'] );
        }
        else
        {
            return FALSE;
        } 
    }
    else
    {
        // perform the request if the requested object exists
        return $B->$class_name->perform( $evt['data'] );
    }
}

/* 
Include and define here data, which are required by other modules.
This means that you must define at least 3 variables: 
- SF_AUTH_MODULE
- SF_OPTION_MODULE
- SF_DEFAULT_MODULE
For detailed info of this vars see below.

Furthermore if you modules require a database connection or some class instances
or packages like PEAR, ADODB, ... you have to include those here.

*/

/***************************
**** Module SET  CONFIG ****
****************************/

// ### These 3 defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',                 'TEST');

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',               'OPTION');

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',              'DEFAULT');

// include system config array else forward to the admin section to start setup
if(file_exists(SF_BASE_DIR . '/admin/modules/common/config/config.php'))
{
    include_once( SF_BASE_DIR . '/admin/modules/common/config/config.php' );  
}
else
{
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
    exit;  
}

?>
