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
 * TEST_CONTACT class 
 *
 */
 
class TEST_CONTACT
{
    /**
     * Test contact 
     * Fill up an string with contact data
     *
     * Structure of the $data array:
     * $data['var']           - array name where to store numbers
     *
     * @param array $data
     */  
    function perform( $data )
    {
            // get var name defined in the public template to store the result
            $_result = & $GLOBALS['B']->$data['var']; 
            
            $_result  = "\nBuster Keaton\n41, Rue Tivoli,\nParis, France\n\n";
            $_result .= "Email: {$B->sys['option']['email']}";     
    }
}

?>
