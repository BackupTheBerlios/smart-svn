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
 * SYSTEM_FILTER_EMAIL_OBFUSCATING class
 *
 */
 
class SYSTEM_FILTER_EMAIL_OBFUSCATING
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
    function SYSTEM_FILTER_EMAIL_OBFUSCATING()
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
     * @param array $data
     */  
    function perform( $data )
    {
        echo str_replace("@", " AT ", ob_get_clean() ); 
    }
}

?>
