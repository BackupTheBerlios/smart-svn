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
 * Admin SETUP module event handler
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_USER' , 'USER');

// Version of this modul
define ( 'MOD_USER_VERSION' , '0.1.3');

// define template event calls for this module
// this event handler build from those variables
// dynamic class function calls:
// Format:
// Class file        = class.USER_definedvar.php
// CLASS             = USER_definedvar
// CLASS Constructor = USER_definedvar() // in php4 // __construct() in php5
//
// The event handler call only one class function:
// perform( $data );
// where $data = the third param of a event call -> $evt['data']
//
define ( 'EVT_USER_LOGIN' ,        'LOGIN');
define ( 'EVT_USER_REGISTER' ,     'REGISTER');
define ( 'EVT_USER_VALIDATE' ,     'VALIDATE');

// register this handler                       
if (FALSE == $B->register_handler( MOD_USER,
                                   array ( 'module'           => MOD_USER,
                                           'event_handler'    => 'user_event_handler',
                                           'menu_visibility'  => TRUE) ))
{
    trigger_error( 'The handler '.MOD_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function user_event_handler( $evt )
{
    global $B;

    // build the whole class name
    $class_name = 'USER_'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . '/admin/modules/user/class.'.$class_name.'.php';
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
            if( SF_DEBUG == TRUE )
            {
                trigger_error('This class file dosent exists: '.$class_file, E_USER_ERROR);
            }        
            return FALSE;
        } 
    }
    else
    {
        // perform the request if the requested object exists
        return $B->$class_name->perform( $evt['data'] );
    }
}

?>
