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
 * The functions of this class are used to register
 * vars, objects.
 * 
 *
 */
 
class sfBase
{
    /**
     * Array of registered var names
     * @var array $_registered_vars
     * @access privat
     */
    var $_registered_vars = array();
    /**
     * Array of registered handler
     * @var array $handler_list
     */
    var $handler_list = array(); 
    
    /**
     * Var register methode
     *
     * @param string $var Var name.     
     * @param string $file File name where ther var is declared.
     * @param string $line Line number
     */
    function register( $var, $file, $line )
    {
        if( FALSE == $this->is_registered( $var ) )
        {
            $this->_registered_vars[$var] = array('file' => $file, 'line' => $line);
        }
        else
        {
            patErrorManager::raiseError( "reg:error", "Register error: {$var}", "Var: {$var}\nFILE: {$file}\nLINE: {$line}\n is registered in: \nFILE: ".$this->_registered_vars[$var]['file']."\nLINE: ".$this->_registered_vars[$var]['line']);
        }
    }
    /**
     * unregister
     *
     * @param string $var Var name.     
     */
    function unregister( $var )
    {
        unset($this->_registered_vars[$var]); 
        if(is_object($this->$var) && (method_exists($this->$var, '_destroy')))
        {
            $this->$var->_destroy();
        }
        unset($this->$var);
    }
    /**
     * isregister 
     *
     * @param string $var Var name.     
     */    
    function is_registered( $var )
    {
        if(isset($this->_registered_vars[$var]))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
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
      * @param array $code Message id to send to all registered handler.
      * @param mixed $data Additional data (optional).
      * @return mixed
      */ 
    function M( $target_id, $code, $data = FALSE )
    {
        $_event = array("target_id" => $target_id,
                        "code"      => $code,
                        "data"      => $data);    
        $descriptor = $this->handler_list[$target_id];
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
            // call the event handler function
            $descriptor["event_handler"]($_event);
        }   
    }    
}

?>
