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
 * TEST_SYS_LOGOUT class 
 *
 */
 
class TEST_SYS_LOGOUT
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
    function TEST_SYS_LOGOUT()
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
