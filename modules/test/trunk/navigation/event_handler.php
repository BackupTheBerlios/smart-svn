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
 * Admin NAVIGATION module event handler
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_NAVIGATION' , 'NAVIGATION');

// Version of this module
define ( 'MOD_NAVIGATION_VERSION' , '0.2');

// register this handler                       
if (FALSE == $B->register_handler( MOD_NAVIGATION,
                                   array ( 'module'          => MOD_NAVIGATION,
                                           'event_handler'   => 'navigation_event_handler',
                                           'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_NAVIGATION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function navigation_event_handler( $evt )
{
    global $B;

    // accept only a string which contains the chars A-Z and _
    if(preg_match("/[A-Z_]+/",$evt['code']))
    {
        // build the whole class name
        $class_name = 'NAVIGATION_'.$evt['code'];
    }
    elseif( SF_DEBUG == TRUE )
    {
        trigger_error('This action '.$evt['code'].' isnt allowed. Only A-Z_ chars are accepted.: '.$class_file, E_USER_ERROR);
        return FALSE;
    } 
    else
    {
        return FALSE;
    }
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load the required class
        $class_file = SF_BASE_DIR . '/admin/modules/navigation/class.'.$class_name.'.php';
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
