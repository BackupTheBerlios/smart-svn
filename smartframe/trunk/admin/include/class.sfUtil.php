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
     * Add slashes if magic_quotes are disabled
     *
     * @return string base location
     */ 
    function stripSlashes( $var )
    {
        $magicQuote = get_magic_quotes_gpc();

        if ( $magicQuote == 0 )
        {   
            return $var;
        }
        else
        {
            return stripslashes($var);
        }
    }    
    
    /**
     * Make unique md5_string
     *
     * @return string unique md5 string 
     */
    function unique_md5_str()
    {
        mt_srand((double) microtime()*1000000);
        return md5(str_replace(".","",$_SERVER["REMOTE_ADDR"]) + mt_rand(100000,999999)+uniqid(microtime()));    
    } 
    
    /**
     * delete_dir_tree
     *
     * Delete directory and content recursive
     *
     * @param string $dir Directory
     */
    function delete_dir_tree( $dir )
    {
        if ( (($handle = @opendir( $dir ))) != FALSE )
        {
            while ( (( $file = readdir( $handle ) )) != false )
            {
                if ( ( $file == "." ) || ( $file == ".." ) )
                {
                    continue;
                }
                if ( @is_dir( $dir . '/' . $file ) )
                {
                    $this->delete_dir_tree( $dir . '/' . $file );
                }
                else
                {
                    if( (@unlink( $dir . '/' . $file )) == FALSE )
                    {
                        trigger_error( "Can not delete content in dir tree: {$dir}/{$file}", E_USER_ERROR  );
                    }
                }
            }
            @closedir( $handle );
            if( (@rmdir( $dir )) == FALSE )
            {
                trigger_error( "Can not remvoe dir: {$dir}", E_USER_ERROR  );
            }
        }
        else
        {
            trigger_error( "Can not delete content dir: {$dir}", E_USER_ERROR  );
        }
    } 
    /**
     * Returns the correct link for the back/pages/next links
     *
     * @return string Url
     */
    function getQueryString()
    {
        // Sort out query string to prevent messy urls
        $querystring = array();
        $qs = array();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $qs = explode('&', str_replace('&amp;', '&', $_SERVER['QUERY_STRING']));
            for ($i=0, $cnt=count($qs); $i<$cnt; $i++) {
                list($name, $value) = explode('=', $qs[$i]);
                $qs[$name] = $value;
                unset($qs[$i]);
            }
        }

        foreach ($qs as $name => $value) {
            $querystring[] = $name . '=' . $value;
        }

        return '?' . implode('&', $querystring);
    }    
}

?>
