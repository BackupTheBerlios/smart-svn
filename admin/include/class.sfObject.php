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
 
class sfObject
{
    /**
     * Array of registered var names
     * @var array $_registered_vars
     * @access privat
     */
    var $_registered_vars = array();

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
}

?>
