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
 * Event handler class 
 *
 */
class sfEvent
{
    /**
     * Array of registered handler
     * @var array $handler_list
     */
    var $handler_list = array(); 
    
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
     * Send a directed event
     *
     * @param string $target_id Name of the handler.  
     * @param array $code Message id to send to all registered handler.
     * @param mixed $data Additional data (optional).
     */ 
    function directed( $target_id, $code, $data = FALSE )
    {
        $_event = array("target_id" => $target_id,
                        "code"      => $code,
                        "data"      => $data);    
        $descriptor = $this->handler_list[$target_id];
        // call the event handler function
        return $descriptor["event_handler"]($_event);  
    }
    
    /**
     * Alias Send a directed event
     *
     * @param string $target_id Name of the handler.  
     * @param array $code Message id to send to all registered handler.
     * @param mixed $data Additional data (optional).
     */ 
    function M( $target_id, $code, $data = FALSE )
    {
        $_event = array("target_id" => $target_id,
                        "code"      => $code,
                        "data"      => $data);    
        $descriptor = $this->handler_list[$target_id];
        // call the event handler function
        return $descriptor["event_handler"]($_event);  
    }    

    /**
     * Alias Send a broadcast event
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
            // call the event handler function
            $descriptor["event_handler"]($_event);
        }   
    }

    /**
     * Send a broadcast event
     *
     * @param array $code Message id to send to all registered handler.
     * @param mixed $data Additional data (optional).
     */ 
    function broadcast( $code, $data = FALSE )
    {
        $_event = array("code"      => $code,
                        "data"      => $data);  
        foreach( $this->handler_list as $descriptor )
        {
            // call the event handler function
            $descriptor["event_handler"]($_event);
        }   
    }

    /**
     * Check if a handler exists
     *
     * @param string $target Name of a handler.
     * @return bool true or false.
     */
    function is_handler( $target )
    {
        if( isset($this->handler_list[$target]) )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    } 
}

?>
