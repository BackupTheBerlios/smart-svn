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
 *
 * Check variables types
 */
 
class sfSecureGPC
{
    /**
     * get
     *
     * @param mixed $var Var to check.     
     * @param string $type Type of the variable.
     * @return mixed
     */
    function get( &$var, $type = 'int' )
    {
        switch ($type)
        {
            case 'int':
                    return sfSecureGPC::_getInt( $var );
                break;
            case 'string':
                    return sfSecureGPC::_getString( $var );
                break;
            case 'sqlstring':
                    return sfSecureGPC::_getSqlString( $var );
                break;                
            default:
                    patErrorManager::raiseError( 'GPC', 'Unknow var type:.' . $type);
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
            patErrorManager::raiseWarning( 'GPC', 'Wrong INT', $var  );                    
        }
        return (int) $var;
    }
    /**
     * _getString
     *
     * Check if the value is a string which
     * contains a-zA-Z chars
     *
     * @param mixed $var Variable. 
     * @return string
     * @access privat
     */    
    function _getString( $var )
    {
        if(empty($var))
        {
            return '';
        }
        
        if(preg_match("/[^a-zA-Z]/",$var))
        {
            patErrorManager::raiseWarning( 'GPC', 'Wrong STRING', $var  );
            return preg_replace("/[^a-zA-Z]+/", "", $var);
        }
        else
        {
            return $var;
        }
    }
    /**
     * _getSqlString
     *
     * Check sql intrusion of a string
     *
     * @param mixed $var Variable. 
     * @return string
     * @access privat
     * @todo check string
     */   
    function _getSqlString( $var )
    {
        if(empty($var))
        {
            return '';
        }
        
        return $var;
    }    
}

?>
