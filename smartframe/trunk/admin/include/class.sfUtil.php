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
        if(SF_SECTION == 'admin')
        {
            $base_dirname = dirname(dirname($_SERVER['PHP_SELF']));
        }
        elseif(SF_SECTION == 'public')
        {
            $base_dirname = dirname($_SERVER['PHP_SELF']);
        }
        else
        {
            $base_dirname = '';
        }
        
        if($base_dirname == '/' )
            $base_dirname = '';

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
}

?>
