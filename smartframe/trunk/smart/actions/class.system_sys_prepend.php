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
 * system_sys_prepend class - Run code before the application logic
 *
 */
 
class system_sys_prepend
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
    function system_sys_prepend()
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
     * Run filters and other stuff before the application logic  
     *
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Manual order the filter priority 

        // add headers
        F( SYSTEM_FILTER , 'add_headers' ); 
    }    
}

?>
