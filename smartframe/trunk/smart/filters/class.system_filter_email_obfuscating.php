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
 * system_filter_email_obfuscating class
 *
 */
 
class system_filter_email_obfuscating
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function system_filter_email_obfuscating()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * EMAIL_OBFUSCATING
     *
     * @param string $content
     * @return string filtered content
     */  
    function perform( & $content )
    {
        return str_replace("@", " AT ", $content );       
    }
}

?>
