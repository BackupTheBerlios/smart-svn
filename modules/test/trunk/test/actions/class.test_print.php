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
 * test_print class 
 *
 */
 
class test_print
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
    function test_print()
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
     * Assign a string passed from a template event call
     * to a template variable
     *
     * The $data array is the third argument of a template event call function.
     * Structure of the $data array:
     * $data['var']           - template var name to store string data
     * $data['string']        - string content
     *
     * @param array $data
     */  
    function perform( $data )
    {
        // get var name defined in the public template to store the result
        $_result = & $this->B->$data['var']; 
        
        // assign the string to the template variable
        $_result  = $data['string'];     
    }
}

?>
