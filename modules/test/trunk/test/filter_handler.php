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
define( 'TEST_FILTER' , 'test' );

// register this filter                      
if (FALSE == $B->register_filter( TEST_FILTER,
                                  array ( 'filter'         => TEST_FILTER,
                                          'filter_handler' => 'test_filter_handler') ))
{
    trigger_error( 'The module filter handler '.TEST_FILTER.' is already registered: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The filter handler function
function test_filter_handler( $evt )
{
    global $B;

    // build the whole class name
    $class_name = 'test_filter'.$evt['code'];
    
    // check if this object was previously declared
    if(!is_object($B->$class_name))
    {
        // dynamic load of the required class
        $class_file = SF_BASE_DIR . 'modules/test/filters/class.'.$class_name.'.php';
        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $B->$class_name = & new $class_name();
            // perform the request
            return $B->$class_name->perform( $evt['data'] );
        }
        return FALSE;
    }
    else
    {
        // perform the request if the requested object already exist
        return $B->$class_name->perform( $evt['data'] );
    }
}

?>
