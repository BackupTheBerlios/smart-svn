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
 * test_sys_logout class 
 *
 */
 
class test_sys_logout
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
    function test_sys_logout()
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
     * If a logout request was done
     *
     * @param array $data
     */
    function perform( $data )
    {            
        // include here additional clean up code. 
        // destroying sessions,....
            
        // exit to the main public page
        @header('Location: '.SF_BASE_LOCATION.'/index.php');
        exit;
    }    
}

?>
