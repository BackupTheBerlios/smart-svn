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
 * Util class 
 *
 */
 
class sfUtil
{
    /**
     * Get the base location
     *
     * @return string base location
     */
    function base_location()
    {
        $base_dirname = dirname($_SERVER['PHP_SELF']);
        if(preg_match("/(.*)(\/|\\\\)$/", $base_dirname, $match))
        {
            $base_dirname = $match[1];
        }
    
        // Build the http protocol referrer
        //
        if(preg_match("/^http([s]?)/i", $_SERVER['SCRIPT_URI'], $tmp))
        {
            $http = 'http' . $tmp[1] . '://';
        }
        elseif(preg_match("/^http([s]?)/i", $_SERVER["HTTP_REFERER"], $tmp))
        {
            $http = 'http' . $tmp[1] . '://';
        }
        elseif(preg_match("/^on$/i", $_ENV["HTTPS"]))
        {
            $http = 'https://';
        }    
        else
        {
            $http = 'http://';
        }    
        return $http . $_SERVER['HTTP_HOST'] . $base_dirname;
    }
    /**
     * Add slashes if magic_quotes are disabled
     *
     * @return string base location
     */ 
    function addSlashes( $var )
    {
        $magicQuote = get_magic_quotes_gpc();

        if ( $magicQuote == 0 )
        {   
            return addslashes($var);
        }
        else
        {
            return $var;
        }
    }
    /**
     *  md5 password encrypting for patUser
     *
     *
     *  @access public
     *  @param  string  $password
     *  @return string  encrypted password
     */
    function    md5_crypter( $password )
    {
        return md5($password);
    }    
}

?>
