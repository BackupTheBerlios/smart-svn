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
 * SYSTEM_SYS_PREPEND class - Run code before the application logic
 *
 */
 
class SYSTEM_SYS_PREPEND
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
    function SYSTEM_SYS_PREPEND()
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
    
        // detect spam bots
        $this->B->F( SYSTEM_FILTER , 'SPAM_BOTS' );

        // add headers
        $this->B->F( SYSTEM_FILTER , 'ADD_HEADERS' );
        
        // do logging
        $this->B->F( SYSTEM_FILTER , 'LOGGING' );  
    }    
}

?>
