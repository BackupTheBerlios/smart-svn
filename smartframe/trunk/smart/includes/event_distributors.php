<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 *
 * Contains event distributor functions
 *
 */

/**
 * Array of registered handler
 * @var array $handler_list
 */
$handler_list = array(); 


/**
  * Check if a handler exist
  *
  * @param string $target Name of a handler.
  * @return bool true or false.
  */
function is_handler( $target )
{
    global $handler_list;
    
    return isset( $handler_list[$target] );
}

/**
  * Handler register methode
  *
  * @param string $target Name of the handler. 
  * @param array $descriptor Array of identification data of the handler.
  * @param bool True on success else false
  */
function register_module( $target, $descriptor )
{
    global $handler_list;
    
    if(isset( $handler_list[$target] ))
    {
        return FALSE;
    }
    
    $handler_list[$target] = $descriptor;
    return TRUE;
}

/**
  * Send a directed event (to a module or to the system)
  *
  * @param string $target_id Name of the handler.  
  * @param array $code Message id to send to the registered handler.
  * @param mixed $data Additional data (optional).
  * @return mixed FALSE if handler dosent exist or isnt defined
  */ 
function M( $target_id, $code, $data = FALSE )
{
    global $handler_list;

    // check if such a handler is registered
    if(!isset( $handler_list[$target_id]) )
    {
        trigger_error("This handler function isnt defined: ".$target_id."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        return FALSE;
    }  

    // build the whole class name
    $class_name = 'action_' . $target_id . '_' . $code;
    
    // check if this object was previously declared
    if(!is_object($GLOBALS[$class_name]))
    {
        // path to the modules action class 
        $class_file = SF_BASE_DIR . 'modules/' . $target_id . '/actions/class.'.$class_name.'.php';
        // path to the system action class
        if( $target_id == 'system' )
        {
            // dynamic load the required class 
            $class_file = SF_BASE_DIR . 'smart/actions/class.'.$class_name.'.php';
        }

        if(file_exists($class_file))
        {
            include_once($class_file);
            // make instance
            $GLOBALS[$class_name] = & new $class_name();
            
            // validate the request
            if( FALSE == $GLOBALS[$class_name]->validate( $data ) )
            {
                trigger_error("Validation fails on class: ".$class_name."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_WARNING);
                return FALSE;
            }
            // perform the request
            return $GLOBALS[$class_name]->perform( $data );
        }
    }
    else
    {
        // validate the request
        if( FALSE == $GLOBALS[$class_name]->validate( $data ) )
        {
            trigger_error("Validation fails on class: ".$class_name."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_WARNING);
            return FALSE;
        }  
        
        // perform the request if the requested object exists
        return $GLOBALS[$class_name]->perform( $data );
    }
}

/**
  * Send a broadcast event (to all modules and the system)
  *
  * @param array $code Message id to send to all modules.
  * @param mixed $data Additional data (optional).
  */ 
function B( $code, $data = FALSE )
{
    global $handler_list; 

    foreach( $handler_list as $target_id => $val )
    {
        // build the whole class name
        $class_name = 'action_' . $target_id . '_' . $code;

        // check if this object was previously declared
        if(!is_object($GLOBALS[$class_name]))
        {
            // path to the modules action class 
            $class_file = SF_BASE_DIR . 'modules/' . $target_id . '/actions/class.'.$class_name.'.php';
            // path to the system action class
            if( $target_id == 'system' )
            {
                // dynamic load the required class 
                $class_file = SF_BASE_DIR . 'smart/actions/class.'.$class_name.'.php';
            }

            if(file_exists($class_file))
            {
                include_once($class_file);
                // make instance
                $GLOBALS[$class_name] = & new $class_name();
            
                // validate the request
                if( FALSE == $GLOBALS[$class_name]->validate( $data ) )
                {
                    trigger_error("Validation fails on class: ".$class_name."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_WARNING);
                    continue;
                }
                // perform the request
                $GLOBALS[$class_name]->perform( $data );
            }
        }
        else
        {
            // validate the request
            if( FALSE == $GLOBALS[$class_name]->validate( $data ) )
            {
                trigger_error("Validation fails on class: ".$class_name."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_WARNING);
                continue;
            }  
        
            // perform the request if the requested object exists
            $GLOBALS[$class_name]->perform( $data );
        }
    }
 
}   

?>