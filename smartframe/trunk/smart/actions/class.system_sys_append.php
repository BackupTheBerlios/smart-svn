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
 * system_sys_append class - Run code after the application logic
 *
 */
 
class system_sys_append
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
    function system_sys_append()
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
    /**
     * Run filters and other stuff after the application logic  
     *
     *
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Manual order the filter priority 
    
        // Remove space before and after the template output
        $this->B->F( SYSTEM_FILTER , 'trim_output' );

        // email obfuscation
        $this->B->F( SYSTEM_FILTER , 'email_obfuscating' );
    }    
}

?>
