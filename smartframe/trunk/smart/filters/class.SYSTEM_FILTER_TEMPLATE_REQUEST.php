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
 * SYSTEM_FILTER_TRIM_OUTPUT class - Remove space before and after the template output
 *
 */
 
class SYSTEM_FILTER_TRIM_OUTPUT
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
    function SYSTEM_FILTER_TRIM_OUTPUT()
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
     * Remove space before and after the template output
     *
     * @param array $data
     */  
    function perform( $data )
    {
        echo trim( ob_get_clean() );    
    }
}

?>
