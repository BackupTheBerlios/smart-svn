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
 * SYSTEM_SYS_APPEND class - Run code after the application logic
 *
 */
 
class SYSTEM_SYS_APPEND
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
    function SYSTEM_SYS_APPEND()
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
        $this->B->F( SYSTEM_FILTER , 'TRIM_OUTPUT' );

        // email obfuscation
        $this->B->F( SYSTEM_FILTER , 'EMAIL_OBFUSCATING' );
    }    
}

?>
