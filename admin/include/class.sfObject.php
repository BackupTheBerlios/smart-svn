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
     * @return mixed true if not yet registered else info string of the previous declared var
     */
    function register( $var, $file, $line )
    {
        if(FALSE == ($_dump = $this->_is_registered( $var, $file, $line )))
        {
            $this->_registered_vars[$var] = array('file' => $file, 'line' => $line);
            return TRUE;
        }
        else
        {
            return $_dump;
        }
    }

    function unregister( $var )
    {
        unset($this->_registered_vars[$var]); 
        if(is_object($this->$var) && (method_exists($this->$var, '_destroy')))
        {
            $this->$var->_destroy();
        }
        unset($this->$var);
    }
    
    function _is_registered( $var, $file, $line )
    {
        if(isset($this->_registered_vars[$var]))
        {
            $_dump  = "WARNING: Double Var Declaration<br /><br />";
            $_dump .= "DEC VAR: " .$var."<br />";
            $_dump .= "DEC FILE: ".$file."<br />";
            $_dump .= "DEC LINE: ".$line."<br />"; 
            $_dump .= "<br>Was previously declared:<br /><br />"; 
            $_dump .= $this->dump($var);
            return $_dump;
        }
        else
        {
            return FALSE;
        }
    }
    
    function dump( $var )
    {
        if(isset($this->_registered_vars[$var]))
        {
            $_dump = "VAR: ".$var."<br />";
            $_dump .= "FILE: ".$this->_registered_vars[$var]['file']."<br />";
            $_dump .= "LINE: ".$this->_registered_vars[$var]['line']."<br />";
            $_dump .= var_dump( $this->$var );    
            return $_dump;
        }
        else
        {
            return FALSE;
        }
    }
  
}

?>
