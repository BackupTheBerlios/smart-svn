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
 * Base Object class 
 *
 * It include event handler methods and serves to encapsulate global variables
 *
 */
 
class sfBase
{
    /**
     * Array of registered handler
     * @var array $handler_list
     */
    var $handler_list = array(); 
    /**
      * Check if a handler exist
      *
      * @param string $target Name of a handler.
      * @return bool true or false.
      */
    function is_handler( $target )
    {
        return isset($this->handler_list[$target]);
    }
    
    /**
      * Handler register methode
      *
      * @param string $target Name of the handler.     
      * @param array $descriptor Array of identification data of the handler.
      * @param bool True on success else false
      */
    function register_handler( $target, $descriptor )
    {
        if(isset($this->handler_list[$target]))
        {
            return FALSE;
        }
        $this->handler_list[$target] = $descriptor;
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
        $_event = array("target_id" => $target_id,
                        "code"      => $code,
                        "data"      => $data);  
        
        // check if such a handler exist
        if(!isset($this->handler_list[$target_id]))
        {
            trigger_error("This handler function isnt defined: ".$target_id."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }  
        
        // get the function name
        $descriptor = $this->handler_list[$target_id];
        
        // check if the defined function handler exist
        if(!function_exists($descriptor['event_handler']))
        {
            trigger_error("This handler function dosent exists: ".$descriptor['event_handler']."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;        
        }
        
        // call the event handler function
        return $descriptor['event_handler']($_event);  
    }    

    /**
      * Send a broadcast event (to all modules and the system)
      *
      * @param array $code Message id to send to all registered handler.
      * @param mixed $data Additional data (optional).
      */ 
    function B( $code, $data = FALSE )
    {
        $_event = array("code"      => $code,
                        "data"      => $data);  
        foreach( $this->handler_list as $descriptor )
        {
            // check if the defined function handler exist
            if(!function_exists($descriptor["event_handler"]))
            {
                trigger_error("This handler function dosent exists: ".$descriptor['event_handler']."\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);      
            }
            else
            {
                // call the event handler function
                $descriptor["event_handler"]($_event);
            }
        }   
    }    
}

?>
