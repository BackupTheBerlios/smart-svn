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
     * Array of registered events
     * @var array $event_list 
     */    
    var $event_list   = array();

    /**
     * Handler register methode
     *
     * @param string $target Name of the handler.     
     * @param array $descriptor Array of identification data of the handler.
     */
    function register_handler( $target, $descriptor )
    {
        if(isset($this->handler_list[$target]))
        {
            return patErrorManager::raiseError( 'handler:error', 'Handler exist', "The handler {$target} exist!"  );        
        }
        $this->handler_list[$target] = $descriptor;
        return TRUE;
    }

    /**
     * Register a directed event which should be executed later
     * by $this->run()
     *
     * @param string $target_id Name of the handler.  
     * @param array $code Message id to send to the handler.
     * @param mixed $data Additional data (optional).
     */    
    function directed( $target_id, $code, $data = FALSE )
    {
        $this->event_list[] = array(
                                    "type"      => EVT_TYPE_DIRECTED,
                                    "target_id" => $target_id,
                                    "code"      => $code,
                                    "data"      => $data);
    } 

    /**
     * Send a directed event
     *
     * @param string $target_id Name of the handler.  
     * @param array $code Message id to send to all registered handler.
     * @param mixed $data Additional data (optional).
     */ 
    function directed_run( $target_id, $code, $data = FALSE )
    {
        $_event = array("target_id" => $target_id,
                        "code"      => $code,
                        "data"      => $data);    
        $descriptor = $this->handler_list[$target_id];
        // call the event handler function
        $descriptor["event_handler"]($_event);  
        unset( $_event );
    }

    /**
     * Register a broadcast event which should be executed later
     * by $this->run()
     * 
     * @param array $code Message id to send to the handler.
     * @param mixed $data Additional data (optional).
     */ 
    function broadcast( $code, $data = FALSE )
    {
        $this->event_list[] = array(
                                    "type"      => EVT_TYPE_BROADCAST,
                                    "target_id" => NULL,
                                    "code"      => $code,
                                    "data"      => $data);
    }

    /**
     * Send a broadcast event
     *
     * @param array $code Message id to send to all registered handler.
     * @param mixed $data Additional data (optional).
     */ 
    function broadcast_run( $code, $data = FALSE )
    {
        $_event = array("code"      => $code,
                        "data"      => $data);  
        foreach( $this->handler_list as $descriptor )
        {
            // call the event handler function
            $descriptor["event_handler"]($_event);
        }   
        unset( $_event );
    }

    /**
     * Get the next registered event
     *
     */    
    function _get()
    {
        return( array_shift($this->event_list) );
    } 

    /**
     * Execute all registered event
     *
     */ 
    function run()
    {
        // Execute all registered events
        //
        while($tmp_event = $this->_get())
        {
            switch( $tmp_event["type"] )
            {
                case EVT_TYPE_BROADCAST:
                    foreach( $this->handler_list as $descriptor )
                    {
                        // call the event handler function
                        $descriptor["event_handler"]($tmp_event);
                    }
                    break;                
                case EVT_TYPE_DIRECTED:
                    $descriptor = $this->handler_list[$tmp_event["target_id"]];
                    // call the event handler function
                    $descriptor["event_handler"]($tmp_event);
                    break;                
            }
            
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
