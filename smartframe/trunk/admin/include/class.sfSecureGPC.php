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
 * Secure GPC class 
 * check validity of variables which comes from _GET _POST _COOKIE _REQUEST ...
 *
 * Check variables types
 */
 
class sfSecureGPC
{
    /**
     * get
     * check validity of a variable
     *
     * @param mixed $var Var to check.     
     * @param string $type Type of the variable.
     * @param string $preg_str preg string format eg. "[a-zA-Z]".
     * @return mixed
     */
    function get( &$var, $type = 'int', $preg_str = FALSE )
    {
        switch ($type)
        {
            case 'int':
                    return sfSecureGPC::_getInt( $var );
                break;
            case 'string':
                    return sfSecureGPC::_getString( $var, $preg_str );
                break;            
            default:
                    return FALSE;
                break;
        }
    }
    /**
     * _getInt
     *
     * Check if the value is int
     *
     * @param mixed $var Variable. 
     * @return int
     * @access privat
     */
    function _getInt( $var )
    {
        if(empty($var))
        {
            return 0;
        }
        
        if(preg_match("/[^0-9]+/",$var))
        {
            return FALSE;                   
        }
        return (int) $var;
    }
    /**
     * _getString
     *
     * Check if the value is a string which
     * contains a-zA-Z chars or the preg value in $preg_str
     *
     * @param mixed $var Variable. 
     * @param string $preg_str preg string format eg. "[a-zA-Z]".
     * @return string
     * @access privat
     */    
    function _getString( $var, $preg_str )
    {
        if(empty($var))
        {
            return '';
        }
        
        if( FALSE !== $preg_str )
        {
            if( preg_match("/{$preg_str}/",$var) )
            {        
                return $var;   
            }
            else
            {
                return FALSE;             
            }
        }
        
        if(preg_match("/[a-zA-Z]+/",$var))
        {
            return $var;
        }
        else
        {
            return FALSE;            
        }
    }
}

?>
