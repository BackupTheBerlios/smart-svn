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
 * DEFAULT module filter handler
 *
 *
 */

// Check if this file is included in the SMART environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// Name of the filter module
define( 'DEFAULT_FILTER' , 'default' );

// register this filter                      
if (FALSE == register_filter( EARCHIVE_FILTER,
                              array ( 'filter'         => DEFAULT_FILTER,
                                      'filter_handler' => 'default_filter_handler') ))
{
    trigger_error( 'The module filter handler '.DEFAULT_FILTER.' is already registered: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The filter handler function
function default_filter_handler( $evt )
{
    // build the whole class name
    $class_name = 'default_filter_'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($GLOBALS[$class_name]))
    {
        // dynamic load of the required class
        $class_file = SF_BASE_DIR . 'modules/default/filters/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $GLOBALS[$class_name] = & new $class_name();
            // perform the request
            return $GLOBALS[$class_name]->perform( $evt['data'] );
        }
        return FALSE;
    }
    else
    {
        // perform the request if the requested object already exist
        return $GLOBALS[$class_name]->perform( $evt['data'] );
    }
}


?>
