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

/**
 *
 * Contains event distributor functions and a module register function
 * 
 * Those distributor functions dynamically loads module action classes, 
 * make instances, execute there validate and there perform methodes.
 *
 * Currently there are 2 distributor functions:
 * 
 * - Directed Event Distributor
 *   It directs an event to a specific module
 *
 * - Broadcast Event Distributor
 *   It directs an event to all modules
 *
 *
 * Furthermore one function to return an action object
 *
 */

/**
 * Array of registered modules
 * @var array $module_list
 */
$module_list = array(); 


/**
  * Check if a module exist
  *
  * @param string $target Name of a module.
  * @return bool true or false.
  */
function is_module( $target )
{
    global $module_list;
    
    return isset( $module_list[$target] );
}

/**
  * Module register methode
  *
  * @param string $target Name of the module. 
  * @param array $descriptor Array of module identification data.
  * @param bool True on success else false (if module was previously declared)
  */
function register_module( $target, $descriptor )
{
    global $module_list;
    
    if(isset( $module_list[$target] ))
    {
        return FALSE;
    }
    
    $module_list[$target] = $descriptor;
    return TRUE;
}

/**
  * Send a directed event (to a module or to the system)
  *
  * @param string $target_id Name of the module.  
  * @param array $code Name of the module action class, which performs on this event.
  * @param mixed $data Additional data (optional).
  * @return mixed FALSE if module dosent exist or isnt defined. null if an action class dosent exists
  */ 
function M( $target_id, $code, $data = FALSE )
{
    global $module_list;

    // check if such a module is registered
    if(!isset( $module_list[$target_id]) )
    {
        trigger_error("This module isnt defined: ".$target_id."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        return FALSE;
    }  

    // build the whole module action class name
    $class_name = 'action_' . $target_id . '_' . $code;
    
    // check if this object was previously declared
    if( !isset($GLOBALS[$class_name]) )
    {
        // path to the modules action class 
        $class_file = SF_BASE_DIR . 'modules/' . $target_id . '/actions/class.'.$class_name.'.php';
        // path to the system action class if the target was the system module
        if( $target_id == 'system' )
        {
            // dynamic load of the required class 
            $class_file = SF_BASE_DIR . 'smart/actions/class.'.$class_name.'.php';
        }

        if(@file_exists($class_file))
        {
            include_once($class_file);
            // make instance of the module action class
            $GLOBALS[$class_name] = & new $class_name();
            
            // validate the request
            if( FALSE == $GLOBALS[$class_name]->validate( $data ) )
            {
                trigger_error("Validation fails on class: ".$class_name."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_WARNING);
                return FALSE;
            }
            // perform on the request
            return $GLOBALS[$class_name]->perform( $data );
        }
        else
        {
            return NULL;
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
  * Return a module action class object
  *
  * @param string $target_id Name of the module.  
  * @param array $code Name of the module action class.
  * @return mixed null if requested module action class dosent exist or module action object
  */ 
function & M_OBJ( $target_id, $code )
{
    global $module_list;

    // check if such a module is registered
    if(!isset( $module_list[$target_id]) )
    {
        trigger_error("This module isnt defined: ".$target_id."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        return FALSE;
    }  

    // build the whole module action class name
    $class_name = 'action_' . $target_id . '_' . $code;
    
    // path to the modules action class 
    $class_file = SF_BASE_DIR . 'modules/' . $target_id . '/actions/class.'.$class_name.'.php';
    // path to the system action class if the target was the system module
    if( $target_id == 'system' )
    {
        // dynamic load of the required class 
        $class_file = SF_BASE_DIR . 'smart/actions/class.'.$class_name.'.php';
    }

    if(@file_exists($class_file))
    {
        include_once($class_file);
        // make instance of the module action class
        return new $class_name();
    }
    else
    {
        return NULL;
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
    global $module_list; 

    foreach( $module_list as $target_id => $val )
    {
        // build the whole class name
        $class_name = 'action_' . $target_id . '_' . $code;

        // check if this object was previously declared
        if( !isset($GLOBALS[$class_name]) )
        {
            // path to the modules action class 
            $class_file = SF_BASE_DIR . 'modules/' . $target_id . '/actions/class.'.$class_name.'.php';
            // path to the system action class
            if( $target_id == 'system' )
            {
                // dynamic load the required class 
                $class_file = SF_BASE_DIR . 'smart/actions/class.'.$class_name.'.php';
            }

            if(@file_exists($class_file))
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