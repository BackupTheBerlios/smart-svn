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
 * test_contact class 
 *
 */
 
class test_contact
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
    function test_contact()
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
     * Test contact 
     * Fill up a string with contact data
     *
     * Structure of the $data array:
     * $data['var']           - array name where to store contact data
     *
     * @param array $data
     */  
    function perform( $data )
    {
        // get var name defined in the public template to store the result
        $_result = & $this->B->$data['var']; 
            
        $_result  = "\nBuster Keaton\n41, Rue Tivoli,\nParis, France\n\n";
        $_result .= "Email: {$GLOBALS['B']->sys['option']['email']}";     
    }
}

?>
