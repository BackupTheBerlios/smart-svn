<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * fetch emails from email accounts.
 * This script should be executed by a cronjob
 */

/*
 * Secure include of files from this script
 */
define ('SF_SECURE_INCLUDE', 1);

// Define the absolute path to the Framework base
//
define ('SF_BASE_DIR', dirname(dirname(dirname(dirname(__FILE__)))) . '/');


// just a dirty workaround class to load 
// config.php variables
class workaround
{
    function workaround()
    {
    }
}

// load the config file and open the view to fetch emails
class connect
{
    function connect()
    {
        $this->B = & $GLOBALS['B'];
        
        // include system config array $this->B->sys
        if(file_exists(SF_BASE_DIR . 'modules/common/config/config.php'))
            include_once( SF_BASE_DIR . 'modules/common/config/config.php' );  

        // if setup was done
        if($this->B->sys['info']['status'] == TRUE)
        {
            $url = "{$this->B->sys['option']['url']}/index.php?view=fetch_emails&passID={$this->B->sys['option']['passID']}";
            $handle = fopen( $url, "r" );
            fclose($handle);
        }
        else
        {
            exit;
        }    
  }
}



$B = new workaround();
$connect = new connect();


?>
